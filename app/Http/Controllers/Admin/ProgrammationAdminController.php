<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgrammationAdminController extends Controller
{
    public function index(Request $request)
    {
        $date = (string) $request->query('date', now()->toDateString());
        $cinema = (string) $request->query('cinema', 'all'); // all | idCin

        // sécurise date
        try {
            $dateObj = Carbon::parse($date)->startOfDay();
            $date = $dateObj->toDateString();
        } catch (\Throwable $e) {
            $dateObj = now()->startOfDay();
            $date = $dateObj->toDateString();
        }

        $prevDate = $dateObj->copy()->subDay()->toDateString();
        $nextDate = $dateObj->copy()->addDay()->toDateString();

        $cinemas = Schema::hasTable('cinema')
            ? DB::table('cinema')->select('idCin', 'nomCin')->orderBy('nomCin')->get()
            : collect();

        $films = Schema::hasTable('film')
            ? DB::table('film')->select('idFil', 'nomFil')->orderBy('nomFil')->get()
            : collect();

        $langues = (Schema::hasTable('langue') && Schema::hasColumn('langue', 'idLan'))
            ? DB::table('langue')->select('idLan', 'langue')->orderBy('langue')->get()
            : collect();

        // salles filtrées selon le cinéma sélectionné (pour le modal "Ajouter")
        $salles = collect();
        if (Schema::hasTable('salle')) {
            $sq = DB::table('salle')->select('idSal', 'nomSal', 'idCin')->orderBy('nomSal');

            if ($cinema !== 'all' && ctype_digit($cinema)) {
                $sq->where('idCin', (int)$cinema);
            }

            $salles = $sq->get();
        }

        // Séances du jour
        $seances = collect();
        if (Schema::hasTable('seance')) {
            $q = DB::table('seance');

            // joins
            if (Schema::hasTable('film')) {
                $q->leftJoin('film', 'film.idFil', '=', 'seance.idFil');
            }
            if (Schema::hasTable('cinema')) {
                $q->leftJoin('cinema', 'cinema.idCin', '=', 'seance.idCin');
            }

            // salle optionnelle (si idSal existe dans seance)
            if (Schema::hasTable('salle') && Schema::hasColumn('seance', 'idSal')) {
                $q->leftJoin('salle', 'salle.idSal', '=', 'seance.idSal');
            }

            // langue optionnelle (si idLan existe dans seance)
            if (Schema::hasTable('langue') && Schema::hasColumn('seance', 'idLan')) {
                $q->leftJoin('langue', 'langue.idLan', '=', 'seance.idLan');
            }

            // filtres
            if (Schema::hasColumn('seance', 'datHeuSea')) {
                $q->whereDate('seance.datHeuSea', $date);
            }

            if ($cinema !== 'all' && ctype_digit($cinema)) {
                $q->where('seance.idCin', (int)$cinema);
            }

            // select
            $q->select([
                'seance.idSea',
                'seance.idFil',
                'seance.idCin',
                Schema::hasColumn('seance', 'idSal') ? 'seance.idSal' : DB::raw('NULL as idSal'),
                Schema::hasColumn('seance', 'idLan') ? 'seance.idLan' : DB::raw('NULL as idLan'),
                'seance.datHeuSea',
                'seance.priSea',

                Schema::hasTable('film') ? 'film.nomFil as film_title' : DB::raw("'Film' as film_title"),
                Schema::hasTable('cinema') ? 'cinema.nomCin as cinema_name' : DB::raw("NULL as cinema_name"),
                (Schema::hasTable('salle') && Schema::hasColumn('seance', 'idSal')) ? 'salle.nomSal as salle_name' : DB::raw("NULL as salle_name"),
                (Schema::hasTable('langue') && Schema::hasColumn('seance', 'idLan')) ? 'langue.langue as langue_name' : DB::raw("NULL as langue_name"),
            ]);

            $q->orderBy('seance.datHeuSea');

            $seances = $q->get();
        }

        return view('admin.G_prog', [
            'date' => $date,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'cinema' => $cinema,

            'cinemas' => $cinemas,
            'films' => $films,
            'langues' => $langues,
            'salles' => $salles,

            'seances' => $seances,
        ]);
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('seance')) {
            return back()->withErrors(['prog' => 'Table seance introuvable.']);
        }

        $data = $request->validate([
            'idFil' => ['required', 'integer'],
            'idCin' => ['required', 'integer'],
            'idSal' => ['nullable', 'integer'],
            'idLan' => ['nullable', 'integer'],
            'date'  => ['required', 'date'],
            'time'  => ['required'],
            'priSea' => ['required', 'numeric', 'min:0'],
        ]);

        $dt = Carbon::parse($data['date'] . ' ' . $data['time']);

        $insert = [
            'idFil' => (int)$data['idFil'],
            'idCin' => (int)$data['idCin'],
            'datHeuSea' => $dt,
            'priSea' => $data['priSea'],
        ];

        // optionnels selon colonnes existantes
        if (Schema::hasColumn('seance', 'idSal')) {
            $insert['idSal'] = !empty($data['idSal']) ? (int)$data['idSal'] : null;
        }
        if (Schema::hasColumn('seance', 'idLan')) {
            $insert['idLan'] = !empty($data['idLan']) ? (int)$data['idLan'] : null;
        }

        DB::table('seance')->insert($insert);

        return redirect()->route('admin.prog', [
            'date' => Carbon::parse($data['date'])->toDateString(),
            'cinema' => (string)$data['idCin'],
        ])->with('success', 'Séance ajoutée.');
    }

    public function update(Request $request, $idSea)
    {
        if (!Schema::hasTable('seance')) {
            return back()->withErrors(['prog' => 'Table seance introuvable.']);
        }

        $idSea = (int)$idSea;

        $data = $request->validate([
            'idFil' => ['required', 'integer'],
            'idCin' => ['required', 'integer'],
            'idSal' => ['nullable', 'integer'],
            'idLan' => ['nullable', 'integer'],
            'date'  => ['required', 'date'],
            'time'  => ['required'],
            'priSea' => ['required', 'numeric', 'min:0'],
        ]);

        $dt = Carbon::parse($data['date'] . ' ' . $data['time']);

        $update = [
            'idFil' => (int)$data['idFil'],
            'idCin' => (int)$data['idCin'],
            'datHeuSea' => $dt,
            'priSea' => $data['priSea'],
        ];

        if (Schema::hasColumn('seance', 'idSal')) {
            $update['idSal'] = !empty($data['idSal']) ? (int)$data['idSal'] : null;
        }
        if (Schema::hasColumn('seance', 'idLan')) {
            $update['idLan'] = !empty($data['idLan']) ? (int)$data['idLan'] : null;
        }

        DB::table('seance')->where('idSea', $idSea)->update($update);

        return redirect()->route('admin.prog', [
            'date' => Carbon::parse($data['date'])->toDateString(),
            'cinema' => (string)$data['idCin'],
        ])->with('success', 'Séance modifiée.');
    }

    public function destroy(Request $request, $idSea)
    {
        if (!Schema::hasTable('seance')) {
            return back()->withErrors(['prog' => 'Table seance introuvable.']);
        }

        $idSea = (int)$idSea;

        DB::table('seance')->where('idSea', $idSea)->delete();

        return back()->with('success', 'Séance supprimée.');
    }

    // JSON: salles du cinéma sélectionné
    public function sallesByCinema(Request $request)
    {
        if (!Schema::hasTable('salle')) {
            return response()->json([]);
        }

        $idCin = (int) $request->query('cinema', 0);
        if ($idCin <= 0) {
            return response()->json([]);
        }

        $rows = DB::table('salle')
            ->where('idCin', $idCin)
            ->select('idSal', 'nomSal')
            ->orderBy('nomSal')
            ->get();

        return response()->json($rows);
    }
}
