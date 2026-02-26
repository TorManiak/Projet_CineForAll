<?php
// app/Http/Controllers/FilmController.php (ENTIER) - 0 JS "logique", état auto comme la maquette

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FilmController extends Controller
{
    public function show(Request $request, $film)
    {
        // Film (table film avec PK idFil)
        $filmRow = DB::table('film')->where('idFil', (int)$film)->first();
        if (!$filmRow) abort(404);

        // ----- Infos film (inchangé si tu avais déjà ça) -----
        $people = DB::table('jouer')
            ->join('personnalite', 'personnalite.idPer', '=', 'jouer.idPer')
            ->join('type_de_role', 'type_de_role.idRolPer', '=', 'jouer.idRolPer')
            ->where('jouer.idFil', (int)$filmRow->idFil)
            ->select('personnalite.nomPer', 'personnalite.prePer', 'type_de_role.libRol')
            ->get();

        $realisateurs = $people
            ->filter(fn($p) => in_array(mb_strtolower((string)$p->libRol), ['realisateur','co-realisateur']))
            ->map(fn($p) => trim($p->prePer . ' ' . $p->nomPer))
            ->values()->all();

        $casting = $people
            ->filter(fn($p) => mb_strtolower((string)$p->libRol) === 'acteur')
            ->map(fn($p) => trim($p->prePer . ' ' . $p->nomPer))
            ->values()->all();

        $langues = [];
        if (Schema::hasTable('film_langue') && Schema::hasTable('langue')) {
            $langues = DB::table('film_langue')
                ->join('langue', 'langue.idLan', '=', 'film_langue.idLan')
                ->where('film_langue.idFil', (int)$filmRow->idFil)
                ->pluck('langue.langue')
                ->toArray();
        }

        // ----- Réservation (auto-select pour afficher directement comme la maquette) -----
        $cinemas = collect();
        $dates = collect();
        $seances = collect();

        $selectedCinema = $request->query('cinema'); // idCin
        $selectedDate   = $request->query('date');   // Y-m-d

        $defaultSeanceId = null;

        if (Schema::hasTable('seance') && Schema::hasTable('salle') && Schema::hasTable('cinema')) {

            $cinemas = DB::table('seance')
                ->join('salle', 'salle.idSal', '=', 'seance.idSal')
                ->join('cinema', 'cinema.idCin', '=', 'salle.idCin')
                ->where('seance.idFil', (int)$filmRow->idFil)
                ->select('cinema.idCin', 'cinema.nomCin')
                ->distinct()
                ->orderBy('cinema.nomCin')
                ->get();

            // Auto-sélection du 1er cinéma si rien dans l'URL
            if (!$selectedCinema && $cinemas->count() > 0) {
                $selectedCinema = (string)$cinemas->first()->idCin;
            }

            if ($selectedCinema) {
                $dates = DB::table('seance')
                    ->join('salle', 'salle.idSal', '=', 'seance.idSal')
                    ->join('cinema', 'cinema.idCin', '=', 'salle.idCin')
                    ->where('seance.idFil', (int)$filmRow->idFil)
                    ->where('cinema.idCin', (int)$selectedCinema)
                    ->selectRaw('DATE(seance.datSea) as d')
                    ->distinct()
                    ->orderBy('d')
                    ->pluck('d');

                // Auto-sélection de la 1ère date si rien dans l'URL
                if (!$selectedDate && $dates->count() > 0) {
                    $selectedDate = (string)$dates->first();
                }
            }

            // Redirige pour avoir une URL stable (cinema/date) => affichage direct (comme la maquette)
            $needRedirect = false;
            if ($selectedCinema && $request->query('cinema') !== $selectedCinema) $needRedirect = true;
            if ($selectedDate && $request->query('date') !== $selectedDate) $needRedirect = true;

            if ($needRedirect) {
                return redirect()->route('films.show', [
                    'film' => $filmRow->idFil,
                    'cinema' => $selectedCinema,
                    'date' => $selectedDate,
                ]);
            }

            if ($selectedCinema && $selectedDate) {
                $seances = DB::table('seance')
                    ->join('salle', 'salle.idSal', '=', 'seance.idSal')
                    ->join('cinema', 'cinema.idCin', '=', 'salle.idCin')
                    ->where('seance.idFil', (int)$filmRow->idFil)
                    ->where('cinema.idCin', (int)$selectedCinema)
                    ->whereDate('seance.datSea', $selectedDate)
                    ->select('seance.idSea', 'seance.datSea', 'seance.heuSea', 'seance.plaRes', 'cinema.idCin', 'cinema.nomCin')
                    ->orderBy('seance.heuSea')
                    ->get();

                // Auto-sélection du 1er horaire dispo (pour que le bouton rouge soit utilisable)
                $firstAvailable = $seances->first(fn($s) => (int)$s->plaRes > 0);
                $defaultSeanceId = $firstAvailable ? (int)$firstAvailable->idSea : null;
            }
        }

        return view('show', [
            'film' => $filmRow,
            'realisateurs' => $realisateurs,
            'casting' => $casting,
            'langues' => $langues,

            'cinemas' => $cinemas,
            'dates' => $dates,
            'seances' => $seances,
            'selectedCinema' => $selectedCinema,
            'selectedDate' => $selectedDate,
            'defaultSeanceId' => $defaultSeanceId,
        ]);
    }
}
