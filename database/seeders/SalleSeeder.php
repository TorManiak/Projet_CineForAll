<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        // Nettoyage (optionnel)
        DB::table('salle')->truncate();

        DB::table('salle')->insert([
            // Cinéma 1 (Paris)
            ['idCin' => 1, 'nomSal' => 'Salle 1',      'nbSie' => 120, 'typSal' => 'Normal'],
            ['idCin' => 1, 'nomSal' => 'Salle 2',      'nbSie' => 80,  'typSal' => '3D'],
            ['idCin' => 1, 'nomSal' => 'Salle IMAX',   'nbSie' => 200, 'typSal' => 'IMAX'],

            // Cinéma 2 (Lyon)
            ['idCin' => 2, 'nomSal' => 'Salle 1',      'nbSie' => 110, 'typSal' => 'Normal'],
            ['idCin' => 2, 'nomSal' => 'Salle 2',      'nbSie' => 90,  'typSal' => '3D'],
            ['idCin' => 2, 'nomSal' => 'Salle Dolby',  'nbSie' => 160, 'typSal' => 'DOLBY'],

            // Cinéma 3 (Marseille)
            ['idCin' => 3, 'nomSal' => 'Salle 1',      'nbSie' => 100, 'typSal' => 'Normal'],
            ['idCin' => 3, 'nomSal' => 'Salle 2',      'nbSie' => 75,  'typSal' => '3D'],
            ['idCin' => 3, 'nomSal' => 'Salle Premium','nbSie' => 140, 'typSal' => 'PREMIUM'],

            // Cinéma 4 (Lille)
            ['idCin' => 4, 'nomSal' => 'Salle 1',      'nbSie' => 95,  'typSal' => 'Normal'],
            ['idCin' => 4, 'nomSal' => 'Salle 2',      'nbSie' => 70,  'typSal' => '3D'],
            ['idCin' => 4, 'nomSal' => 'Salle XL',     'nbSie' => 150, 'typSal' => 'XL'],
        ]);
    }
}
