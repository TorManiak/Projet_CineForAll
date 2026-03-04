<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seance')->insert([
            ['idFil'=>1,
            'idCin'=>1,
            'datHeuSea'=>'2026-03-03 02:19:00',
            'priSea'=>13.90,
            'idSal'=>1,
            'idLan'=>1    ],
        ]);
    }
}
