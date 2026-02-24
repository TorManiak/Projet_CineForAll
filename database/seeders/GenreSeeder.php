<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genre')->insert([
            ['libGen'=> 'Action'],
            ['libGen'=> 'Aventure'],
            ['libGen'=> 'Animation'],
            ['libGen'=> 'Comedie'],
            ['libGen'=> 'Drama'],
            ['libGen'=> 'Science-fiction'],
            ['libGen'=> 'Famille'],
            ['libGen'=> 'Fantaisie'],
            ['libGen'=> 'Histoire'],
            ['libGen'=> 'Documentaire'],
            ['libGen'=> 'Horreur'],
            ['libGen'=> 'Comedie-Musicale'],
            ['libGen'=> 'Nature'],
            ['libGen'=> 'Criminel'],
            ['libGen'=> 'Romance'],
            ['libGen'=> 'Police'],
            ['libGen'=> 'Thriller'],


        ]);
    }
}
