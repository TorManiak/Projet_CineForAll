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


        ]);
    }
}
