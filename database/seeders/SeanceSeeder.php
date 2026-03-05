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
            'idLan'=>1,
            'malVoyEnt'=>true    ],
            ['idFil'=>5,
                'idCin'=>1,
                'datHeuSea'=>'2026-03-09 10:00:00',
                'priSea'=>12.00,
                'idSal'=>3,
                'idLan'=>2,
                'malVoyEnt'=>true],
            ['idFil'=>3,
                'idCin'=>2,
                'datHeuSea'=>'2026-03-09 14:20:01',
                'priSea'=>13.90,
                'idSal'=>4,
                'idLan'=>1,
                'malVoyEnt'=>false],
            ['idFil'=>4,
                'idCin'=>4,
                'datHeuSea'=>'2026-03-10 02:00:00',
                'priSea'=>10.17,
                'idSal'=>12,
                'idLan'=>3,
                'malVoyEnt'=>true],
            ['idFil'=>15,
                'idCin'=>2,
                'datHeuSea'=>'2026-03-10 15:30:00',
                'priSea'=>69.69,
                'idSal'=>2,
                'idLan'=>1,
                'malVoyEnt'=>true],
        ]);
    }
}
