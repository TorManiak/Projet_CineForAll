<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgrammationController extends Controller
{
    public function index(Request $request)
    {
        $date = (string) $request->query('date', now()->toDateString());
        $cinema = (string) $request->query('cinema', 'all');

        try {
            $dateObj = Carbon::parse($date)->startOfDay();
            $date = $dateObj->toDateString();
        } catch (\Throwable $e) {
            $dateObj = now()->startOfDay();
            $date = $dateObj->toDateString();
        }

        $prevDate = $dateObj->copy()->subDay()->toDateString();
        $nextDate = $dateObj->copy()->addDay()->toDateString();

        $cinemas = collect();
        if (Schema::hasTable('cinema')) {
            $cinemas = DB::table('cinema')
                ->select('idCin', 'nomCin')
                ->orderBy('nomCin', 'asc')
                ->get();
        }

        $seances = collect();
        if (Schema::hasTable('seance')) {
            $q = DB::table('seance')
                ->leftJoin('film', 'film.idFil', '=', 'seance.idFil')
                ->leftJoin('cinema', 'cinema.idCin', '=', 'seance.idCin');

            if (Schema::hasTable('salle') && Schema::hasColumn('seance', 'idSal')) {
                $q->leftJoin('salle', 'salle.idSal', '=', 'seance.idSal');
            }

            if (Schema::hasTable('langue') && Schema::hasColumn('seance', 'idLan')) {
                $q->leftJoin('langue', 'langue.idLan', '=', 'seance.idLan');
            }

            if (Schema::hasColumn('seance', 'datHeuSea')) {
                $q->whereDate('seance.datHeuSea', $date);
            }

            if ($cinema !== 'all' && ctype_digit($cinema)) {
                $q->where('seance.idCin', (int) $cinema);
            }

            $q->select([
                'seance.idSea',
                'seance.datHeuSea',
                'seance.priSea',
                Schema::hasColumn('seance', 'malVoyEnt') ? 'seance.malVoyEnt' : DB::raw('0 as malVoyEnt'),
                'film.nomFil as film_title',
                'cinema.nomCin as cinema_name',
                Schema::hasTable('salle') && Schema::hasColumn('seance', 'idSal')
                    ? 'salle.nomSal as salle_name'
                    : DB::raw('NULL as salle_name'),
                Schema::hasTable('langue') && Schema::hasColumn('seance', 'idLan')
                    ? 'langue.langue as langue_name'
                    : DB::raw('NULL as langue_name'),
            ]);

            $q->orderBy('seance.datHeuSea', 'asc');

            $seances = $q->get();
        }

        return view('programmation', [
            'date' => $date,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'cinema' => $cinema,
            'cinemas' => $cinemas,
            'seances' => $seances,
        ]);
    }
}
