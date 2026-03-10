<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $filter = (string) $request->query('filter', 'all'); // all | upcoming | past

        $userId =
            Auth::id()
            ?? data_get(session('user'), 'id')
            ?? data_get(session('utilisateur'), 'id')
            ?? session('user_id')
            ?? session('idUti')
            ?? data_get(session('user'), 'idUti')
            ?? data_get(session('utilisateur'), 'idUti');

        if (!$userId) {
            return redirect('/connexion');
        }

        $reservations = collect();

        if (Schema::hasTable('reservations')) {
            $reservations = $this->fromReservationsTable($userId, $search, $filter);
        } elseif (Schema::hasTable('reservation')) {
            $reservations = $this->fromReservationTable($userId, $search, $filter);
        } elseif (Schema::hasTable('avoir')) {
            $reservations = $this->fromAvoirTable($userId, $search, $filter);
        }

        return view('reservation', [
            'reservations' => $reservations,
            'search'       => $search,
            'filter'       => $filter,
        ]);
    }

    public function store(Request $request)
    {
        $idSea = (int) $request->input('idSea');

        $userId =
            Auth::id()
            ?? data_get(session('user'), 'idUti')
            ?? data_get(session('user'), 'id')
            ?? data_get(session('utilisateur'), 'idUti')
            ?? data_get(session('utilisateur'), 'id')
            ?? session('idUti')
            ?? session('user_id');

        if (!$userId) {
            return redirect('/connexion');
        }

        if (!$idSea || !Schema::hasTable('seance') || !Schema::hasTable('reservation')) {
            return back()->withErrors(['reservation' => 'Réservation indisponible (séances non configurées).']);
        }

        $nbPlaces = (int)($request->input('nbPlaces', 1));
        if ($nbPlaces <= 0) $nbPlaces = 1;

        try {
            DB::transaction(function () use ($idSea, $userId, $nbPlaces) {
                $seance = DB::table('seance')->where('idSea', $idSea)->lockForUpdate()->first();
                if (!$seance) {
                    throw new \RuntimeException('Séance introuvable.');
                }

                $insert = [
                    'idSea'  => $idSea,
                    'idUti'  => (int)$userId,
                    'datRes' => now(),
                    'status' => 'en attente',
                ];

                if (Schema::hasColumn('reservation', 'nbPlaces')) {
                    $insert['nbPlaces'] = $nbPlaces;
                } elseif (Schema::hasColumn('reservation', 'nbPla')) {
                    $insert['nbPla'] = $nbPlaces;
                }

                DB::table('reservation')->insert($insert);
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['reservation' => $e->getMessage()]);
        }

        return redirect('/reservation')->with('success', 'Réservation effectuée.');
    }

    /**
     * SUPPRESSION réservation (utilisateur)
     */
    public function destroy($idRes)
    {
        $idRes = (int) $idRes;

        $userId =
            Auth::id()
            ?? data_get(session('user'), 'idUti')
            ?? data_get(session('user'), 'id')
            ?? data_get(session('utilisateur'), 'idUti')
            ?? data_get(session('utilisateur'), 'id')
            ?? session('idUti')
            ?? session('user_id');

        if (!$userId) {
            return redirect('/connexion');
        }

        // On ne gère la suppression que si la table "reservation" existe (c’est ton cas)
        if (!Schema::hasTable('reservation')) {
            return back()->withErrors(['reservation' => 'Suppression indisponible (table reservation absente).']);
        }

        // Vérifier que la réservation appartient à l’utilisateur
        $q = DB::table('reservation')->where('idRes', $idRes);

        if (Schema::hasColumn('reservation', 'idUti')) {
            $q->where('idUti', (int)$userId);
        } elseif (Schema::hasColumn('reservation', 'user_id')) {
            $q->where('user_id', (int)$userId);
        }

        $deleted = $q->delete();

        if (!$deleted) {
            return back()->withErrors(['reservation' => 'Réservation introuvable ou non autorisée.']);
        }

        return redirect('/reservation')->with('success', 'Réservation supprimée.');
    }

//    public function seatPlan($idSea)
//    {
//        $idSea = (int)$idSea;
//
//        if (!Schema::hasTable('seance')) {
//            abort(404);
//        }
//
//        $q = DB::table('seance')
//            ->leftJoin('cinema', 'cinema.idCin', '=', 'seance.idCin')
//            ->leftJoin('film', 'film.idFil', '=', 'seance.idFil')
//            ->where('seance.idSea', $idSea)
//            ->select([
//                'seance.idSea',
//                'seance.datHeuSea',
//                'seance.priSea',
//                'cinema.nomCin',
//                'film.nomFil',
//            ]);
//
//        $seance = $q->first();
//
//        if (!$seance) {
//            abort(404);
//        }
//
//        $seance->datSea = null;
//        $seance->heuSea = null;
//
//        return view('seat-plan', ['seance' => $seance]);
//    }

    private function fromReservationsTable($userId, string $search, string $filter)
    {
        $q = DB::table('reservations');

        $userCol = Schema::hasColumn('reservations', 'user_id')
            ? 'user_id'
            : (Schema::hasColumn('reservations', 'idUti') ? 'idUti' : null);

        if ($userCol) $q->where($userCol, $userId);

        if ($search !== '' && Schema::hasColumn('reservations', 'film_title')) {
            $q->where('film_title', 'LIKE', "%{$search}%");
        }

        if (Schema::hasColumn('reservations', 'date_time')) {
            if ($filter === 'upcoming') $q->where('date_time', '>=', now());
            if ($filter === 'past')     $q->where('date_time', '<',  now());
        }

        $orderCol = Schema::hasColumn('reservations', 'date_time') ? 'date_time' : 'id';
        $rows = $q->orderByDesc($orderCol)->get();

        return $rows->map(function ($r) {
            return [
                'idRes'      => $r->idRes ?? $r->id ?? null, // si jamais
                'film_title' => $r->film_title ?? 'Film',
                'poster'     => $r->poster ?? null,
                'date_time'  => $r->date_time ?? null,
                'status'     => $r->status ?? null,
            ];
        });
    }

    private function fromReservationTable($userId, string $search, string $filter)
    {
        $hasSeance = Schema::hasTable('seance');
        $hasFilm   = Schema::hasTable('film');
        $hasCinema = Schema::hasTable('cinema');

        $q = DB::table('reservation');

        if (Schema::hasColumn('reservation', 'idUti')) {
            $q->where('reservation.idUti', $userId);
        } elseif (Schema::hasColumn('reservation', 'user_id')) {
            $q->where('reservation.user_id', $userId);
        }

        if ($hasSeance) {
            $q->leftJoin('seance', 'seance.idSea', '=', 'reservation.idSea');
        }
        if ($hasFilm && $hasSeance) {
            $q->leftJoin('film', 'film.idFil', '=', 'seance.idFil');
        }
        if ($hasCinema && $hasSeance) {
            $q->leftJoin('cinema', 'cinema.idCin', '=', 'seance.idCin');
        }

        if ($search !== '' && $hasFilm && Schema::hasColumn('film', 'nomFil')) {
            $q->where('film.nomFil', 'LIKE', "%{$search}%");
        }

        if ($hasSeance && Schema::hasColumn('seance', 'datHeuSea')) {
            if ($filter === 'upcoming') $q->where('seance.datHeuSea', '>=', now());
            if ($filter === 'past')     $q->where('seance.datHeuSea', '<',  now());
        }

        $q->select([
            'reservation.idRes as idRes',
            DB::raw(($hasFilm ? 'film.nomFil' : "'Film'") . ' as film_title'),
            DB::raw(($hasFilm && Schema::hasColumn('film', 'afiFil') ? 'film.afiFil' : 'NULL') . ' as poster'),
            DB::raw(($hasSeance && Schema::hasColumn('seance', 'datHeuSea') ? "seance.datHeuSea" : 'NULL') . ' as date_time'),
            DB::raw(($hasCinema && Schema::hasColumn('cinema', 'nomCin') ? 'cinema.nomCin' : 'NULL') . ' as cinema_name'),
            DB::raw((Schema::hasColumn('reservation', 'status') ? 'reservation.status' : 'NULL') . ' as status'),
        ]);

        $orderCol = Schema::hasColumn('seance', 'datHeuSea')
            ? 'seance.datHeuSea'
            : (Schema::hasColumn('reservation', 'datRes') ? 'reservation.datRes' : 'reservation.idRes');

        $rows = $q->orderByDesc($orderCol)->get();

        return $rows->map(function ($r) {
            $poster = null;
            if (!empty($r->poster)) {
                $poster = 'img/films/' . ltrim((string)$r->poster, '/');
            }

            $meta = $r->date_time;
            if (!empty($r->cinema_name)) {
                $meta = trim((string)$meta . ' · ' . (string)$r->cinema_name);
            }

            return [
                'idRes'      => $r->idRes ?? null,
                'film_title' => $r->film_title ?? 'Film',
                'poster'     => $poster,
                'date_time'  => $meta,
                'status'     => $r->status ?? null,
            ];
        });
    }

    private function fromAvoirTable($userId, string $search, string $filter)
    {
        $q = DB::table('avoir');

        $userCol = Schema::hasColumn('avoir', 'idUti')
            ? 'idUti'
            : (Schema::hasColumn('avoir', 'user_id') ? 'user_id' : null);

        if ($userCol) $q->where("avoir.$userCol", $userId);

        $hasIdSes = Schema::hasColumn('avoir', 'idSes') && Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'idSes');
        if ($hasIdSes) {
            $q->leftJoin('sessions', 'sessions.idSes', '=', 'avoir.idSes');
        }

        $hasIdFil =
            ($hasIdSes && Schema::hasColumn('sessions', 'idFil'))
            && Schema::hasTable('film')
            && Schema::hasColumn('film', 'idFil');

        if ($hasIdFil) {
            $q->leftJoin('film', 'film.idFil', '=', 'sessions.idFil');
        }

        if ($search !== '' && $hasIdFil && Schema::hasColumn('film', 'nomFil')) {
            $q->where('film.nomFil', 'LIKE', "%{$search}%");
        }

        if ($hasIdSes && Schema::hasColumn('sessions', 'datSes')) {
            if ($filter === 'upcoming') $q->where('sessions.datSes', '>=', now()->toDateString());
            if ($filter === 'past')     $q->where('sessions.datSes', '<',  now()->toDateString());
        }

        $q->select([
            DB::raw(($hasIdFil ? 'film.nomFil' : "'Film'") . ' as film_title'),
            DB::raw(($hasIdFil && Schema::hasColumn('film', 'afiFil') ? 'film.afiFil' : 'NULL') . ' as poster'),
            DB::raw(($hasIdSes ? "sessions.datSes" : 'NULL') . ' as date_time'),
        ]);

        $rows = $q->orderByDesc(Schema::hasColumn('avoir', 'id') ? 'avoir.id' : DB::raw('1'))->get();

        return $rows->map(function ($r) {
            $poster = null;
            if (!empty($r->poster)) {
                $poster = 'img/films/' . ltrim((string)$r->poster, '/');
            }

            return [
                'idRes'      => null, // pas de suppression dans ce mode
                'film_title' => $r->film_title ?? 'Film',
                'poster'     => $poster,
                'date_time'  => $r->date_time ?? null,
                'status'     => null,
            ];
        });
    }
}
