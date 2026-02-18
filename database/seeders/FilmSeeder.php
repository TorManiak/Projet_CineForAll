<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('film')->insert([
            [
                'nomFil' => 'Inception',
                'datFil' => '02:28:00',
                'afiFil' => 'inception.jpg',
                'desFil' => 'Un voleur infiltre les rêves pour implanter une idée.',
                'typeFil' => 'Science-Fiction',
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=YoHD9XEInc0'
            ],
            [
                'nomFil' => 'Interstellar',
                'datFil' => '02:49:00',
                'afiFil' => 'interstellar.jpg',
                'desFil' => 'Exploration spatiale pour sauver l’humanité.',
                'typeFil' => 'Science-Fiction',
                'malVoyEnt' => false,
                'banAnn' => 'https://youtube.com/watch?v=zSWdZVtXT7E'
            ],
            [
                'nomFil' => 'Intouchables',
                'datFil' => '01:52:00',
                'afiFil' => 'intouchables.jpg',
                'desFil' => 'Une amitié improbable entre deux hommes.',
                'typeFil' => 'Comédie',
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=34WIbmXkewU'
            ]
        ]);
    }
}
