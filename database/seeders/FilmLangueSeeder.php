<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilmLangueSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('film_langue')->insert([

            // =========================
            // TOUS LES FILMS ONT VF
            // =========================
            ['idLan' => 1, 'idFil' => 1],
            ['idLan' => 1, 'idFil' => 2],
            ['idLan' => 1, 'idFil' => 3],
            ['idLan' => 1, 'idFil' => 4],
            ['idLan' => 1, 'idFil' => 5],
            ['idLan' => 1, 'idFil' => 6],
            ['idLan' => 1, 'idFil' => 7],
            ['idLan' => 1, 'idFil' => 8],
            ['idLan' => 1, 'idFil' => 9],
            ['idLan' => 1, 'idFil' => 10],
            ['idLan' => 1, 'idFil' => 11],
            ['idLan' => 1, 'idFil' => 12],
            ['idLan' => 1, 'idFil' => 13],
            ['idLan' => 1, 'idFil' => 14],
            ['idLan' => 1, 'idFil' => 15],
            ['idLan' => 1, 'idFil' => 16],
            ['idLan' => 1, 'idFil' => 17],
            ['idLan' => 1, 'idFil' => 18],
            ['idLan' => 1, 'idFil' => 19],
            ['idLan' => 1, 'idFil' => 20],
            ['idLan' => 1, 'idFil' => 21],
            ['idLan' => 1, 'idFil' => 22],
            ['idLan' => 1, 'idFil' => 23],
            ['idLan' => 1, 'idFil' => 24],
            ['idLan' => 1, 'idFil' => 25],
            ['idLan' => 1, 'idFil' => 26],
            ['idLan' => 1, 'idFil' => 27],

            // =========================
            // VOST (beaucoup de films)
            // =========================
            ['idLan' => 2, 'idFil' => 1],
            ['idLan' => 2, 'idFil' => 2],
            ['idLan' => 2, 'idFil' => 5],
            ['idLan' => 2, 'idFil' => 6],
            ['idLan' => 2, 'idFil' => 7],
            ['idLan' => 2, 'idFil' => 8],
            ['idLan' => 2, 'idFil' => 9],
            ['idLan' => 2, 'idFil' => 10],
            ['idLan' => 2, 'idFil' => 11],
            ['idLan' => 2, 'idFil' => 12],
            ['idLan' => 2, 'idFil' => 13],
            ['idLan' => 2, 'idFil' => 14],
            ['idLan' => 2, 'idFil' => 15],
            ['idLan' => 2, 'idFil' => 16],
            ['idLan' => 2, 'idFil' => 17],
            ['idLan' => 2, 'idFil' => 20],
            ['idLan' => 2, 'idFil' => 21],
            ['idLan' => 2, 'idFil' => 22],
            ['idLan' => 2, 'idFil' => 23],
            ['idLan' => 2, 'idFil' => 24],
            ['idLan' => 2, 'idFil' => 26],

            // =========================
            // VO (quelques films seulement)
            // =========================
            ['idLan' => 3, 'idFil' => 18],
            ['idLan' => 3, 'idFil' => 19],
            ['idLan' => 3, 'idFil' => 25],
            ['idLan' => 3, 'idFil' => 27],

        ]);
    }
}
