<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JouerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jouer')->insert([
            [
                'realisateur' => true,
                'idFil' => 1,
            'idPer' => 1,
            'idRolPer' => 2],
        ]);
    }
}
