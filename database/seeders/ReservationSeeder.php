<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reservation')->insert([
            ['idUti'=>3,
            'idSea'=>1,
            'nbPlaces'=>2,
            'datRes'=>now(),
            'status'=>'en attente',    ]
        ]);
    }
}
