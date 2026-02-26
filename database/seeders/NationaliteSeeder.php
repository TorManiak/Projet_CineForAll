<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nationalite')->insert([
            ['nationalite' => 'Francais(e)'],
            ['nationalite' => 'Americain(e)'],
            ['nationalite' => 'Belge'],
            ['nationalite' => 'Japonais'],
            ['nationalite' => 'Chinois'],
    ]);
    }
}
