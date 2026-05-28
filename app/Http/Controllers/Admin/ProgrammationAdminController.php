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

        static $schemaCache = null;
        if ($schemaCache === null) {
            $schemaCache = [
                'tables' => [],
                'columns' => []
            ];

            $tables = Schema::getTables();

            foreach ($tables as $table) {
                $tableName = $table['name'];
                $schemaCache['tables'][$tableName] = true;

                $columns = Schema::getColumns($tableName);
                $schemaCache['columns'][$tableName] = [];

                foreach ($columns as $col) {
                    $colName = $col['name'];
                    $schemaCache['columns'][$tableName][$colName] = true;
                }
            }
        }

        $cinemas = isset($schemaCache['tables']['cinema'])
            ? DB::table('cinema')->select('idCin', 'nomCin')->orderBy('nomCin')->get()
            : collect();

        $films = isset($schemaCache['tables']['film'])
            ? DB::table('film')->select('idFil', 'nomFil')->orderBy('nomFil')->get()
            : collect();

        $langues = (isset($schemaCache['tables']['langue']) && isset($schemaCache['columns']['langue']['idLan']))
            ? DB::table('langue')->select('idLan', 'langue')->orderBy('langue')->get()
            : collect();

        $salles = collect();
        if (isset($schemaCache['tables']['salle'])) {
            $sq = DB::table('salle')->select('idSal', 'nomSal', 'idCin')->orderBy('nomSal');

            if ($cinema !== 'all' && ctype_digit($cinema)) {
                $sq->where('idCin', (int) $cinema);
            }

            $salles = $sq->get();
        }

        $seances = collect();
        if (isset($schemaCache['tables']['seance'])) {
            $q = DB::table('seance');

            if (isset($schemaCache['tables']['film'])) {
                $q->leftJoin('film', 'film.idFil', '=', 'seance.idFil');
            }
            if (isset($schemaCache['tables']['cinema'])) {
                $q->leftJoin('cinema', 'cinema.idCin', '=', 'seance.idCin');
            }
            if (isset($schemaCache['tables']['salle']) && isset($schemaCache['columns']['seance']['idSal'])) {
                $q->leftJoin('salle', 'salle.idSal', '=', 'seance.idSal');
            }
            if (isset($schemaCache['tables']['langue']) && isset($schemaCache['columns']['seance']['idLan'])) {
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
                'seance.idFil',
                'seance.idCin',
                isset($schemaCache['columns']['seance']['idSal']) ? 'seance.idSal' : DB::raw('NULL as idSal'),
                isset($schemaCache['columns']['seance']['idLan']) ? 'seance.idLan' : DB::raw('NULL as idLan'),
                isset($schemaCache['columns']['seance']['malVoyEnt']) ? 'seance.malVoyEnt' : DB::raw('0 as malVoyEnt'),
                'seance.datHeuSea',
                'seance.priSea',

                isset($schemaCache['tables']['film']) ? 'film.nomFil as film_title' : DB::raw("'Film' as film_title"),
                isset($schemaCache['tables']['cinema']) ? 'cinema.nomCin as cinema_name' : DB::raw("NULL as cinema_name"),
                (isset($schemaCache['tables']['salle']) && isset($schemaCache['columns']['seance']['idSal'])) ? 'salle.nomSal as salle_name' : DB::raw("NULL as salle_name"),
                (isset($schemaCache['tables']['langue']) && isset($schemaCache['columns']['seance']['idLan'])) ? 'langue.langue as langue_name' : DB::raw("NULL as langue_name"),
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

        static $seaCol = null;

        if ($seaCol === null) {
            $seaCol = array_flip(Schema::getColumnListing('seance'));
        }

        $rules = [
            'idFil' => ['required', 'integer'],
            'idCin' => ['required', 'integer'],
            'idSal' => ['nullable', 'integer'],
            'idLan' => ['nullable', 'integer'],
            'date'  => ['required', 'date'],
            'time'  => ['required'],
            'priSea' => ['required', 'numeric', 'min:0'],
        ];

        if (isset($seaCol['malVoyEnt'])) {
            $rules['malVoyEnt'] = ['required', 'in:0,1'];
        }

        $data = $request->validate($rules);

        $dt = Carbon::parse($data['date'] . ' ' . $data['time']);

        $insert = [
            'idFil' => (int) $data['idFil'],
            'idCin' => (int) $data['idCin'],
            'datHeuSea' => $dt,
            'priSea' => $data['priSea'],
        ];

        if (isset($seaCol['idSal'])) {
            $insert['idSal'] = !empty($data['idSal']) ? (int) $data['idSal'] : null;
        }
        if (isset($seaCol['idLan'])) {
            $insert['idLan'] = !empty($data['idLan']) ? (int) $data['idLan'] : null;
        }
        if (isset($seaCol['malVoyEnt'])) {
            $insert['malVoyEnt'] = (int) $data['malVoyEnt'];
        }

        DB::table('seance')->insert($insert);

        return redirect()->route('admin.prog', [
            'date' => Carbon::parse($data['date'])->toDateString(),
            'cinema' => (string) $data['idCin'],
        ])->with('success', 'Séance ajoutée.');
    }

    public function update(Request $request, $idSea)
    {
        if (!Schema::hasTable('seance')) {
            return back()->withErrors(['prog' => 'Table seance introuvable.']);
        }

        static $seaCol = null;

        if ($seaCol === null) {
            $seaCol = array_flip(Schema::getColumnListing('seance'));
        }

        $idSea = (int) $idSea;

        $rules = [
            'idFil' => ['required', 'integer'],
            'idCin' => ['required', 'integer'],
            'idSal' => ['nullable', 'integer'],
            'idLan' => ['nullable', 'integer'],
            'date'  => ['required', 'date'],
            'time'  => ['required'],
            'priSea' => ['required', 'numeric', 'min:0'],
        ];

        if (isset($seaCol['malVoyEnt'])) {
            $rules['malVoyEnt'] = ['required', 'in:0,1'];
        }

        $data = $request->validate($rules);

        $dt = Carbon::parse($data['date'] . ' ' . $data['time']);

        $update = [
            'idFil' => (int) $data['idFil'],
            'idCin' => (int) $data['idCin'],
            'datHeuSea' => $dt,
            'priSea' => $data['priSea'],
        ];

        if (isset($seaCol['idSal'])) {
            $update['idSal'] = !empty($data['idSal']) ? (int) $data['idSal'] : null;
        }
        if (isset($seaCol['idLan'])) {
            $update['idLan'] = !empty($data['idLan']) ? (int) $data['idLan'] : null;
        }
        if (isset($seaCol['malVoyEnt'])) {
            $update['malVoyEnt'] = (int) $data['malVoyEnt'];
        }

        DB::table('seance')->where('idSea', $idSea)->update($update);

        return redirect()->route('admin.prog', [
            'date' => Carbon::parse($data['date'])->toDateString(),
            'cinema' => (string) $data['idCin'],
        ])->with('success', 'Séance modifiée.');
    }

    public function destroy(Request $request, $idSea)
    {
        if (!Schema::hasTable('seance')) {
            return back()->withErrors(['prog' => 'Table seance introuvable.']);
        }

        $idSea = (int) $idSea;

        DB::table('seance')->where('idSea', $idSea)->delete();

        return back()->with('success', 'Séance supprimée.');
    }

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
