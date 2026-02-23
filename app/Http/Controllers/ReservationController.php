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

        // Récupération user_id (compatible Auth + sessions custom)
        $userId =
            Auth::id()
            ?? data_get(session('user'), 'id')
            ?? data_get(session('utilisateur'), 'id')
            ?? session('user_id')
            ?? session('idUti');

        // Si jamais user_id introuvable malgré middleware
        if (!$userId) {
            return redirect('/connexion');
        }

        // On tente de récupérer les réservations depuis une table existante
        // (robuste si ta BD n'a pas encore la table "reservations" et utilise "avoir")
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

    private function fromReservationsTable($userId, string $search, string $filter)
    {
        $q = DB::table('reservations');

        // colonnes probables
        $userCol = Schema::hasColumn('reservations', 'user_id') ? 'user_id' : (Schema::hasColumn('reservations', 'idUti') ? 'idUti' : null);
        if ($userCol) $q->where($userCol, $userId);

        // filtre texte
        if ($search !== '' && Schema::hasColumn('reservations', 'film_title')) {
            $q->where('film_title', 'LIKE', "%{$search}%");
        }

        // filtre temps si colonne date_time
        if (Schema::hasColumn('reservations', 'date_time')) {
            if ($filter === 'upcoming') $q->where('date_time', '>=', now());
            if ($filter === 'past')     $q->where('date_time', '<',  now());
        }

        $rows = $q->orderByDesc(Schema::hasColumn('reservations', 'date_time') ? 'date_time' : 'id')->get();

        return $rows->map(function ($r) {
            return [
                'film_title' => $r->film_title ?? 'Film',
                'poster'     => $r->poster ?? null,
                'date_time'  => $r->date_time ?? null,
                'status'     => $r->status ?? null,
            ];
        });
    }

    private function fromReservationTable($userId, string $search, string $filter)
    {
        // même logique que reservations (singulier)
        $q = DB::table('reservation');

        $userCol = Schema::hasColumn('reservation', 'user_id') ? 'user_id' : (Schema::hasColumn('reservation', 'idUti') ? 'idUti' : null);
        if ($userCol) $q->where($userCol, $userId);

        if ($search !== '' && Schema::hasColumn('reservation', 'film_title')) {
            $q->where('film_title', 'LIKE', "%{$search}%");
        }

        if (Schema::hasColumn('reservation', 'date_time')) {
            if ($filter === 'upcoming') $q->where('date_time', '>=', now());
            if ($filter === 'past')     $q->where('date_time', '<',  now());
        }

        $rows = $q->orderByDesc(Schema::hasColumn('reservation', 'date_time') ? 'date_time' : 'id')->get();

        return $rows->map(function ($r) {
            return [
                'film_title' => $r->film_title ?? 'Film',
                'poster'     => $r->poster ?? null,
                'date_time'  => $r->date_time ?? null,
                'status'     => $r->status ?? null,
            ];
        });
    }

    private function fromAvoirTable($userId, string $search, string $filter)
    {
        // Table vue dans ta BD : "avoir"
        // On essaie de joindre sessions + film si les colonnes existent
        $q = DB::table('avoir');

        // user col probable
        $userCol = Schema::hasColumn('avoir', 'idUti') ? 'idUti' : (Schema::hasColumn('avoir', 'user_id') ? 'user_id' : null);
        if ($userCol) $q->where("avoir.$userCol", $userId);

        // jointure sessions si possible
        $hasIdSes = Schema::hasColumn('avoir', 'idSes') && Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'idSes');
        if ($hasIdSes) {
            $q->leftJoin('sessions', 'sessions.idSes', '=', 'avoir.idSes');
        }

        // jointure film si possible
        $hasIdFil =
            ($hasIdSes && Schema::hasColumn('sessions', 'idFil'))
            && Schema::hasTable('film')
            && Schema::hasColumn('film', 'idFil');

        if ($hasIdFil) {
            $q->leftJoin('film', 'film.idFil', '=', 'sessions.idFil');
        }

        // recherche par nom film si jointure OK
        if ($search !== '' && $hasIdFil && Schema::hasColumn('film', 'nomFil')) {
            $q->where('film.nomFil', 'LIKE', "%{$search}%");
        }

        // filtre upcoming/past si sessions a une date/heure
        // (on teste plusieurs noms de colonnes possibles)
        $dateCol = null;
        foreach (['dateSeance', 'date_session', 'date', 'dateHeure', 'date_time', 'horaire'] as $c) {
            if ($hasIdSes && Schema::hasColumn('sessions', $c)) { $dateCol = "sessions.$c"; break; }
        }
        if ($dateCol) {
            if ($filter === 'upcoming') $q->where($dateCol, '>=', now());
            if ($filter === 'past')     $q->where($dateCol, '<',  now());
        }

        // select normalisé
        $q->select([
            DB::raw(($hasIdFil ? 'film.nomFil' : "'Film'") . ' as film_title'),
            DB::raw(($hasIdFil && Schema::hasColumn('film', 'afiFil') ? "film.afiFil" : "NULL") . ' as poster'),
            DB::raw(($dateCol ? $dateCol : "NULL") . ' as date_time'),
        ]);

        $rows = $q->get();

        return $rows->map(function ($r) {
            // poster: on transforme le nom de fichier en chemin public/img/films/
            $poster = null;
            if (!empty($r->poster)) {
                $poster = 'img/films/' . ltrim((string)$r->poster, '/');
            }

            return [
                'film_title' => $r->film_title ?? 'Film',
                'poster'     => $poster,
                'date_time'  => $r->date_time ?? null,
                'status'     => null,
            ];
        });
    }
}
