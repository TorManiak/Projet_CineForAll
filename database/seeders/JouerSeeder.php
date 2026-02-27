<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JouerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jouer')->insert([
            // 1 Inception
            ['idFil' => 1,  'idPer' => 1,  'idRolPer' => 1],
            ['idFil' => 1,  'idPer' => 23, 'idRolPer' => 2],
            ['idFil' => 1,  'idPer' => 24, 'idRolPer' => 2],
            ['idFil' => 1,  'idPer' => 25, 'idRolPer' => 2],

            // 2 Interstellar
            ['idFil' => 2,  'idPer' => 1,  'idRolPer' => 1],
            ['idFil' => 2,  'idPer' => 26, 'idRolPer' => 2],
            ['idFil' => 2,  'idPer' => 27, 'idRolPer' => 2],
            ['idFil' => 2,  'idPer' => 28, 'idRolPer' => 2],

            // 3 La Haine
            ['idFil' => 3,  'idPer' => 2,  'idRolPer' => 1],
            ['idFil' => 3,  'idPer' => 29, 'idRolPer' => 2],
            ['idFil' => 3,  'idPer' => 30, 'idRolPer' => 2],
            ['idFil' => 3,  'idPer' => 31, 'idRolPer' => 2],

            // 4 Amelie
            ['idFil' => 4,  'idPer' => 3,  'idRolPer' => 1],
            ['idFil' => 4,  'idPer' => 32, 'idRolPer' => 2],
            ['idFil' => 4,  'idPer' => 33, 'idRolPer' => 2],

            // 5 Shutter Island
            ['idFil' => 5,  'idPer' => 4,  'idRolPer' => 1],
            ['idFil' => 5,  'idPer' => 23, 'idRolPer' => 2],
            ['idFil' => 5,  'idPer' => 34, 'idRolPer' => 2],
            ['idFil' => 5,  'idPer' => 35, 'idRolPer' => 2],

            // 6 The Dark Knight
            ['idFil' => 6,  'idPer' => 1,  'idRolPer' => 1],
            ['idFil' => 6,  'idPer' => 36, 'idRolPer' => 2],
            ['idFil' => 6,  'idPer' => 37, 'idRolPer' => 2],
            ['idFil' => 6,  'idPer' => 38, 'idRolPer' => 2],

            // 7 Django Unchained
            ['idFil' => 7,  'idPer' => 5,  'idRolPer' => 1],
            ['idFil' => 7,  'idPer' => 39, 'idRolPer' => 2],
            ['idFil' => 7,  'idPer' => 40, 'idRolPer' => 2],
            ['idFil' => 7,  'idPer' => 23, 'idRolPer' => 2],

            // 8 The Revenant
            ['idFil' => 8,  'idPer' => 6,  'idRolPer' => 1],
            ['idFil' => 8,  'idPer' => 23, 'idRolPer' => 2],
            ['idFil' => 8,  'idPer' => 41, 'idRolPer' => 2],
            ['idFil' => 8,  'idPer' => 42, 'idRolPer' => 2],

            // 9 Parasite
            ['idFil' => 9,  'idPer' => 7,  'idRolPer' => 1],
            ['idFil' => 9,  'idPer' => 43, 'idRolPer' => 2],
            ['idFil' => 9,  'idPer' => 44, 'idRolPer' => 2],
            ['idFil' => 9,  'idPer' => 45, 'idRolPer' => 2],

            // 10 The Grand Budapest Hotel
            ['idFil' => 10, 'idPer' => 8,  'idRolPer' => 1],
            ['idFil' => 10, 'idPer' => 46, 'idRolPer' => 2],
            ['idFil' => 10, 'idPer' => 47, 'idRolPer' => 2],
            ['idFil' => 10, 'idPer' => 48, 'idRolPer' => 2],

            // 11 Mad Max: Fury Road
            ['idFil' => 11, 'idPer' => 9,  'idRolPer' => 1],
            ['idFil' => 11, 'idPer' => 41, 'idRolPer' => 2],
            ['idFil' => 11, 'idPer' => 49, 'idRolPer' => 2],
            ['idFil' => 11, 'idPer' => 50, 'idRolPer' => 2],

            // 12 Spirited Away
            ['idFil' => 12, 'idPer' => 10, 'idRolPer' => 1],

            // 13 The Social Network
            ['idFil' => 13, 'idPer' => 11, 'idRolPer' => 1],
            ['idFil' => 13, 'idPer' => 51, 'idRolPer' => 2],
            ['idFil' => 13, 'idPer' => 52, 'idRolPer' => 2],
            ['idFil' => 13, 'idPer' => 53, 'idRolPer' => 2],

            // 14 Whiplash
            ['idFil' => 14, 'idPer' => 12, 'idRolPer' => 1],
            ['idFil' => 14, 'idPer' => 54, 'idRolPer' => 2],
            ['idFil' => 14, 'idPer' => 55, 'idRolPer' => 2],
            ['idFil' => 14, 'idPer' => 56, 'idRolPer' => 2],

            // 15 The Imitation Game
            ['idFil' => 15, 'idPer' => 13, 'idRolPer' => 1],
            ['idFil' => 15, 'idPer' => 57, 'idRolPer' => 2],
            ['idFil' => 15, 'idPer' => 58, 'idRolPer' => 2],
            ['idFil' => 15, 'idPer' => 59, 'idRolPer' => 2],

            // 16 Get Out
            ['idFil' => 16, 'idPer' => 14, 'idRolPer' => 1],
            ['idFil' => 16, 'idPer' => 60, 'idRolPer' => 2],
            ['idFil' => 16, 'idPer' => 61, 'idRolPer' => 2],
            ['idFil' => 16, 'idPer' => 62, 'idRolPer' => 2],

            // 17 La La Land
            ['idFil' => 17, 'idPer' => 12, 'idRolPer' => 1],
            ['idFil' => 17, 'idPer' => 63, 'idRolPer' => 2],
            ['idFil' => 17, 'idPer' => 64, 'idRolPer' => 2],
            ['idFil' => 17, 'idPer' => 65, 'idRolPer' => 2],

            // 18 The Lobster
            ['idFil' => 18, 'idPer' => 15, 'idRolPer' => 1],
            ['idFil' => 18, 'idPer' => 66, 'idRolPer' => 2],
            ['idFil' => 18, 'idPer' => 67, 'idRolPer' => 2],
            ['idFil' => 18, 'idPer' => 68, 'idRolPer' => 2],

            // 19 Moonlight
            ['idFil' => 19, 'idPer' => 16, 'idRolPer' => 1],
            ['idFil' => 19, 'idPer' => 69, 'idRolPer' => 2],
            ['idFil' => 19, 'idPer' => 70, 'idRolPer' => 2],
            ['idFil' => 19, 'idPer' => 71, 'idRolPer' => 2],

            // 20 Arrival
            ['idFil' => 20, 'idPer' => 17, 'idRolPer' => 1],
            ['idFil' => 20, 'idPer' => 72, 'idRolPer' => 2],
            ['idFil' => 20, 'idPer' => 73, 'idRolPer' => 2],
            ['idFil' => 20, 'idPer' => 74, 'idRolPer' => 2],

            // 21 Blade Runner 2049 (correct)
            ['idFil' => 21, 'idPer' => 17, 'idRolPer' => 1],
            ['idFil' => 21, 'idPer' => 63, 'idRolPer' => 2],
            ['idFil' => 21, 'idPer' => 75, 'idRolPer' => 2],
            ['idFil' => 21, 'idPer' => 76, 'idRolPer' => 2],

            // 22 The Shape of Water
            ['idFil' => 22, 'idPer' => 18, 'idRolPer' => 1],
            ['idFil' => 22, 'idPer' => 77, 'idRolPer' => 2],
            ['idFil' => 22, 'idPer' => 78, 'idRolPer' => 2],
            ['idFil' => 22, 'idPer' => 79, 'idRolPer' => 2],

            // 23 Joker
            ['idFil' => 23, 'idPer' => 19, 'idRolPer' => 1],
            ['idFil' => 23, 'idPer' => 80, 'idRolPer' => 2],
            ['idFil' => 23, 'idPer' => 81, 'idRolPer' => 2],
            ['idFil' => 23, 'idPer' => 82, 'idRolPer' => 2],

            // 24 Tenet
            ['idFil' => 24, 'idPer' => 1,  'idRolPer' => 1],
            ['idFil' => 24, 'idPer' => 83, 'idRolPer' => 2],
            ['idFil' => 24, 'idPer' => 84, 'idRolPer' => 2],
            ['idFil' => 24, 'idPer' => 85, 'idRolPer' => 2],

            // 25 Nomadland
            ['idFil' => 25, 'idPer' => 20, 'idRolPer' => 1],
            ['idFil' => 25, 'idPer' => 86, 'idRolPer' => 2],
            ['idFil' => 25, 'idPer' => 87, 'idRolPer' => 2],
            ['idFil' => 25, 'idPer' => 88, 'idRolPer' => 2],

            // 26 Dune
            ['idFil' => 26, 'idPer' => 17, 'idRolPer' => 1],
            ['idFil' => 26, 'idPer' => 89, 'idRolPer' => 2],
            ['idFil' => 26, 'idPer' => 90, 'idRolPer' => 2],
            ['idFil' => 26, 'idPer' => 91, 'idRolPer' => 2],

            // 27 Everything Everywhere All at Once (2 réalisateurs)
            ['idFil' => 27, 'idPer' => 21, 'idRolPer' => 1],
            ['idFil' => 27, 'idPer' => 22, 'idRolPer' => 1],
            ['idFil' => 27, 'idPer' => 92, 'idRolPer' => 2],
            ['idFil' => 27, 'idPer' => 93, 'idRolPer' => 2],
            ['idFil' => 27, 'idPer' => 94, 'idRolPer' => 2],
        ]);
    }
}
