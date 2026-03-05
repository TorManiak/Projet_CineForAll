<?php
// app/Http/Controllers/FilmController.php (ENTIER)

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class FilmController extends Controller
{
    public function show(Request $request, $film)
    {
        $filmId = (int) $film;

        // Film
        $filmRow = DB::table('film')->where('idFil', $filmId)->first();
        if (!$filmRow) {
            abort(404);
        }

        // Infos film (réalisateurs / casting)
        $people = DB::table('jouer')
            ->join('personnalite', 'personnalite.idPer', '=', 'jouer.idPer')
            ->join('type_de_role', 'type_de_role.idRolPer', '=', 'jouer.idRolPer')
            ->where('jouer.idFil', $filmId)
            ->select('personnalite.nomPer', 'personnalite.prePer', 'type_de_role.libRol')
            ->get();

        $realisateurs = $people
            ->filter(fn ($p) => in_array(mb_strtolower((string) $p->libRol), ['realisateur', 'co-realisateur']))
            ->map(fn ($p) => trim($p->prePer . ' ' . $p->nomPer))
            ->values()
            ->all();

        $casting = $people
            ->filter(fn ($p) => mb_strtolower((string) $p->libRol) === 'acteur')
            ->map(fn ($p) => trim($p->prePer . ' ' . $p->nomPer))
            ->values()
            ->all();

        // Langues
        $langues = [];
        if (Schema::hasTable('film_langue') && Schema::hasTable('langue')) {
            $langues = DB::table('film_langue')
                ->join('langue', 'langue.idLan', '=', 'film_langue.idLan')
                ->where('film_langue.idFil', $filmId)
                ->pluck('langue.langue')
                ->toArray();
        }

        // Réservation (seance.idCin + seance.datHeuSea)
        $cinemas = collect();
        $dates = collect();
        $seances = collect();

        $selectedCinema = $request->query('cinema'); // idCin
        $selectedDate   = $request->query('date');   // Y-m-d
        $selectedSeance = $request->query('seance'); // idSea (horaire sélectionné)

        $defaultSeanceId = null;

        if (Schema::hasTable('seance') && Schema::hasTable('cinema')) {

            // Cinémas où le film est programmé
            $cinemas = DB::table('seance')
                ->join('cinema', 'cinema.idCin', '=', 'seance.idCin')
                ->where('seance.idFil', $filmId)
                ->select('cinema.idCin', 'cinema.nomCin')
                ->distinct()
                ->orderBy('cinema.nomCin')
                ->get();

            if (!$selectedCinema && $cinemas->count() > 0) {
                $selectedCinema = (string) $cinemas->first()->idCin;
            }

            if ($selectedCinema) {
                // Dates dispo
                $dates = DB::table('seance')
                    ->where('seance.idFil', $filmId)
                    ->where('seance.idCin', (int) $selectedCinema)
                    ->selectRaw('DATE(seance.datHeuSea) as d')
                    ->distinct()
                    ->orderBy('d')
                    ->pluck('d');

                if (!$selectedDate && $dates->count() > 0) {
                    $selectedDate = (string) $dates->first();
                }
            }

            // URL stable
            $needRedirect = false;
            if ($selectedCinema && $request->query('cinema') !== $selectedCinema) {
                $needRedirect = true;
            }
            if ($selectedDate && $request->query('date') !== $selectedDate) {
                $needRedirect = true;
            }

            if ($needRedirect) {
                return redirect()->route('films.show', [
                    'film'   => $filmId,
                    'cinema' => $selectedCinema,
                    'date'   => $selectedDate,
                ]);
            }

            if ($selectedCinema && $selectedDate) {
                $seances = DB::table('seance')
                    ->join('cinema', 'cinema.idCin', '=', 'seance.idCin')
                    ->where('seance.idFil', $filmId)
                    ->where('seance.idCin', (int) $selectedCinema)
                    ->whereDate('seance.datHeuSea', $selectedDate)
                    ->select([
                        'seance.idSea',
                        'seance.datHeuSea',
                        'seance.priSea',
                        'cinema.idCin',
                        'cinema.nomCin',
                    ])
                    ->orderBy('seance.datHeuSea')
                    ->get();

                // auto-sélection du 1er horaire
                $defaultSeanceId = $seances->count() ? (int) $seances->first()->idSea : null;

                // si URL contient seance=... on la garde
                if ($selectedSeance) {
                    $exists = $seances->firstWhere('idSea', (int) $selectedSeance);
                    if ($exists) {
                        $defaultSeanceId = (int) $selectedSeance;
                    }
                }
            }
        }

        // NOTES (compatible avec ton système session('user'))
        $noteMoyenne = null;
        $nbNotes = 0;
        $maNote = null;

        if (Schema::hasTable('note')) {
            $stats = DB::table('note')
                ->where('idFil', $filmId)
                ->selectRaw('AVG(note) as avg_note, COUNT(*) as nb')
                ->first();

            $noteMoyenne = ($stats && $stats->avg_note !== null) ? (float) $stats->avg_note : null;
            $nbNotes = $stats ? (int) $stats->nb : 0;

            $user = session('user');
            $idUti = ($user && isset($user->idUti)) ? (int) $user->idUti : 0;

            if ($idUti > 0) {
                $row = DB::table('note')
                    ->where('idFil', $filmId)
                    ->where('idUti', $idUti)
                    ->first();

                $maNote = $row ? (float) $row->note : null;
            }
        }

        return view('show', [
            'film'            => $filmRow,
            'realisateurs'    => $realisateurs,
            'casting'         => $casting,
            'langues'         => $langues,

            'cinemas'         => $cinemas,
            'dates'           => $dates,
            'seances'         => $seances,
            'selectedCinema'  => $selectedCinema,
            'selectedDate'    => $selectedDate,
            'defaultSeanceId' => $defaultSeanceId,

            'noteMoyenne'     => $noteMoyenne,
            'nbNotes'         => $nbNotes,
            'maNote'          => $maNote,
        ]);
    }
}
