<?php
// app/Http/Controllers/FilmController.php (ENTIER)
// Fix BDD actuelle : seance = (idSea, idFil, idCin, datHeuSea, priSea)
// => pas de salle, pas de idSal, pas de datSea/heuSea

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

        // ----- Infos film -----
        $people = DB::table('jouer')
            ->join('personnalite', 'personnalite.idPer', '=', 'jouer.idPer')
            ->join('type_de_role', 'type_de_role.idRolPer', '=', 'jouer.idRolPer')
            ->where('jouer.idFil', (int)$filmRow->idFil)
            ->select('personnalite.nomPer', 'personnalite.prePer', 'type_de_role.libRol')
            ->get();

        $realisateurs = $people
            ->filter(fn($p) => in_array(mb_strtolower((string)$p->libRol), ['realisateur', 'co-realisateur']))
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

        // ----- Réservation (auto-select comme la maquette) -----
        $cinemas = collect();
        $dates = collect();
        $seances = collect();

        $selectedCinema = $request->query('cinema'); // idCin
        $selectedDate   = $request->query('date');   // Y-m-d

        $defaultSeanceId = null;

        $hasSeance = Schema::hasTable('seance');
        $hasCinema = Schema::hasTable('cinema');

        // compat si un jour tu ajoutes "plaRes" dans seance
        $hasPlaRes = $hasSeance && Schema::hasColumn('seance', 'plaRes');

        if ($hasSeance && $hasCinema) {

            // Cinémas dispo pour ce film (join direct via seance.idCin)
            $cinemas = DB::table('seance')
                ->join('cinema', 'cinema.idCin', '=', 'seance.idCin')
                ->where('seance.idFil', (int)$filmRow->idFil)
                ->select('cinema.idCin', 'cinema.nomCin')
                ->distinct()
                ->orderBy('cinema.nomCin', 'asc')
                ->get();

            // Auto-sélection du 1er cinéma
            if (!$selectedCinema && $cinemas->count() > 0) {
                $selectedCinema = (string)$cinemas->first()->idCin;
            }

            if ($selectedCinema) {
                // Dates dispo (DATE(datHeuSea))
                $dates = DB::table('seance')
                    ->where('seance.idFil', (int)$filmRow->idFil)
                    ->where('seance.idCin', (int)$selectedCinema)
                    ->selectRaw('DATE(seance.datHeuSea) as d')
                    ->distinct()
                    ->orderBy('d', 'asc')
                    ->pluck('d');

                // Auto-sélection de la 1ère date
                if (!$selectedDate && $dates->count() > 0) {
                    $selectedDate = (string)$dates->first();
                }
            }

            // URL stable (cinema/date)
            $needRedirect = false;
            if ($selectedCinema && $request->query('cinema') !== $selectedCinema) $needRedirect = true;
            if ($selectedDate && $request->query('date') !== $selectedDate) $needRedirect = true;

            if ($needRedirect) {
                return redirect()->route('films.show', [
                    'film'   => $filmRow->idFil,
                    'cinema' => $selectedCinema,
                    'date'   => $selectedDate,
                ]);
            }

            if ($selectedCinema && $selectedDate) {
                // Séances pour ce film + ce cinéma + cette date
                $seances = DB::table('seance')
                    ->join('cinema', 'cinema.idCin', '=', 'seance.idCin')
                    ->where('seance.idFil', (int)$filmRow->idFil)
                    ->where('seance.idCin', (int)$selectedCinema)
                    ->whereDate('seance.datHeuSea', $selectedDate)
                    ->select([
                        'seance.idSea',
                        'seance.datHeuSea',
                        'seance.priSea',
                        'cinema.idCin',
                        'cinema.nomCin',
                    ])
                    // champs "compat" si ta vue show attend datSea/heuSea
                    ->selectRaw('DATE(seance.datHeuSea) as datSea')
                    ->selectRaw('TIME(seance.datHeuSea) as heuSea')
                    // places (si colonne existe, sinon NULL)
                    ->when($hasPlaRes, fn($q) => $q->addSelect('seance.plaRes'))
                    ->when(!$hasPlaRes, fn($q) => $q->selectRaw('NULL as plaRes'))
                    ->orderBy('seance.datHeuSea', 'asc')
                    ->get();

                // Auto-sélection du 1er horaire (si plaRes existe => premier avec plaRes>0, sinon premier)
                if ($hasPlaRes) {
                    $firstAvailable = $seances->first(fn($s) => (int)$s->plaRes > 0);
                    $defaultSeanceId = $firstAvailable ? (int)$firstAvailable->idSea : null;
                } else {
                    $defaultSeanceId = $seances->count() > 0 ? (int)$seances->first()->idSea : null;
                }
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
