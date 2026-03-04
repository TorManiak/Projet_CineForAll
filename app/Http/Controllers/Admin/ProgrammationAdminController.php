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
        $date   = (string) $request->query('date', now()->toDateString()); // Y-m-d
        $cinema = (string) $request->query('cinema', 'all');               // all | idCin

        // sécurise la date
        try {
            $dateObj = Carbon::parse($date)->startOfDay();
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

        $langues = Schema::hasTable('langue')
            ? DB::table('langue')->select('idLan', 'langue')->orderBy('langue')->get()
            : collect();

        // Salles (filtrées selon cinéma choisi)
        $salles = collect();
        if (Schema::hasTable('salle')) {
            $sq = DB::table('salle')->select('idSal', 'nomSal', 'idCin')->orderBy('nomSal');
            if ($cinema !== 'all') $sq->where('idCin', (int)$cinema);
            $salles = $sq->get();
        }

        // Séances du jour
        $seances = collect();
        if (Schema::hasTable('seance')) {
            $q = DB::table('seance')
                ->leftJoin('film', 'film.idFil', '=', 'seance.idFil')
                ->leftJoin('cinema', 'cinema.idCin', '=', 'seance.idCin');

            if (Schema::hasColumn('seance', 'idSal') && Schema::hasTable('salle')) {
                $q->leftJoin('salle', 'salle.idSal', '=', 'seance.idSal');
            }

            if (Schema::hasColumn('seance', 'idLan') && Schema::hasTable('langue')) {
                $q->leftJoin('langue', 'langue.idLan', '=', 'seance.idLan');
            }

            // Ta colonne datetime est datHeuSea (d'après tes screens)
            if (Schema::hasColumn('seance', 'datHeuSea')) {
                $q->whereDate('seance.datHeuSea', $date);
            }

            if ($cinema !== 'all') {
                $q->where('seance.idCin', (int)$cinema);
            }

            $q->select([
                'seance.idSea',
                'seance.idFil',
                'seance.idCin',
                Schema::hasColumn('seance', 'idSal') ? 'seance.idSal' : DB::raw('NULL as idSal'),
                Schema::hasColumn('seance', 'idLan') ? 'seance.idLan' : DB::raw('NULL as idLan'),
                Schema::hasColumn('seance', 'datHeuSea') ? 'seance.datHeuSea' : DB::raw('NULL as datHeuSea'),
                Schema::hasColumn('seance', 'priSea') ? 'seance.priSea' : DB::raw('0 as priSea'),

                DB::raw("COALESCE(film.nomFil, 'Film') as film_title"),
                DB::raw("COALESCE(cinema.nomCin, 'Cinéma') as cinema_name"),
                Schema::hasTable('salle') && Schema::hasColumn('seance', 'idSal')
                    ? DB::raw("COALESCE(salle.nomSal, 'Aucune salle') as salle_name")
                    : DB::raw("'Aucune salle' as salle_name"),
                Schema::hasTable('langue') && Schema::hasColumn('seance', 'idLan')
                    ? DB::raw("COALESCE(langue.langue, '-') as langue_label")
                    : DB::raw("'-' as langue_label"),
            ]);

            $seances = $q->orderBy('seance.datHeuSea')->get();
        }

        return view('admin.G_prog', compact(
            'date',
            'prevDate',
            'nextDate',
            'cinema',
            'cinemas',
            'films',
            'salles',
            'langues',
            'seances'
        ));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('seance')) {
            return back()->withErrors(['prog' => 'Table seance introuvable.']);
        }

        $data = $request->validate([
            'idFil'  => ['required', 'integer'],
            'idCin'  => ['required', 'integer'],
            'idSal'  => ['nullable', 'integer'],
            'idLan'  => ['nullable', 'integer'],
            'date'   => ['required', 'date'],
            'time'   => ['required'],
            'priSea' => ['required', 'numeric', 'min:0'],
        ]);

        $dt = Carbon::parse($data['date'] . ' ' . $data['time'])->format('Y-m-d H:i:s');

        $insert = [
            'idFil'     => (int)$data['idFil'],
            'idCin'     => (int)$data['idCin'],
            'datHeuSea' => $dt,
            'priSea'    => (float)$data['priSea'],
        ];

        if (Schema::hasColumn('seance', 'idSal')) $insert['idSal'] = $data['idSal'] ? (int)$data['idSal'] : null;
        if (Schema::hasColumn('seance', 'idLan')) $insert['idLan'] = $data['idLan'] ? (int)$data['idLan'] : null;

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

        $data = $request->validate([
            'idFil'  => ['required', 'integer'],
            'idCin'  => ['required', 'integer'],
            'idSal'  => ['nullable', 'integer'],
            'idLan'  => ['nullable', 'integer'],
            'date'   => ['required', 'date'],
            'time'   => ['required'],
            'priSea' => ['required', 'numeric', 'min:0'],
        ]);

        $dt = Carbon::parse($data['date'] . ' ' . $data['time'])->format('Y-m-d H:i:s');

        $update = [
            'idFil'     => (int)$data['idFil'],
            'idCin'     => (int)$data['idCin'],
            'datHeuSea' => $dt,
            'priSea'    => (float)$data['priSea'],
        ];

        if (Schema::hasColumn('seance', 'idSal')) $update['idSal'] = $data['idSal'] ? (int)$data['idSal'] : null;
        if (Schema::hasColumn('seance', 'idLan')) $update['idLan'] = $data['idLan'] ? (int)$data['idLan'] : null;

        DB::table('seance')->where('idSea', (int)$idSea)->update($update);

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

        // paramètres pour revenir sur le bon jour/filtre après suppression
        $date = (string)$request->query('date', now()->toDateString());
        $cinema = (string)$request->query('cinema', 'all');

        DB::table('seance')->where('idSea', (int)$idSea)->delete();

        return redirect()->route('admin.prog', [
            'date' => $date,
            'cinema' => $cinema,
        ])->with('success', 'Séance supprimée.');
    }
}
