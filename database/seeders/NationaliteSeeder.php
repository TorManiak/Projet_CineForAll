<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationaliteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nationalite')->insert([
            ['idNat' => 1,  'nationalite' => 'Francais(e)'],
            ['idNat' => 2,  'nationalite' => 'Americain(e)'],
            ['idNat' => 3,  'nationalite' => 'Belge'],
            ['idNat' => 4,  'nationalite' => 'Japonais(e)'],
            ['idNat' => 5,  'nationalite' => 'Chinois(e)'],

            ['idNat' => 6,  'nationalite' => 'Britannique'],
            ['idNat' => 7,  'nationalite' => 'Canadien(ne)'],
            ['idNat' => 8,  'nationalite' => 'Autrichien(ne)'],
            ['idNat' => 9,  'nationalite' => 'Australien(ne)'],
            ['idNat' => 10, 'nationalite' => 'Mexicain(e)'],

            ['idNat' => 11, 'nationalite' => 'Coreen(ne) du Sud'],
            ['idNat' => 12, 'nationalite' => 'Suedois(e)'],
            ['idNat' => 13, 'nationalite' => 'Irlandais(e)'],
            ['idNat' => 14, 'nationalite' => 'Norvegien(ne)'],
            ['idNat' => 15, 'nationalite' => 'Cubain(e)'],

            ['idNat' => 16, 'nationalite' => 'Malaisien(ne)'],
            ['idNat' => 17, 'nationalite' => 'Sud-Africain(e)'],
            ['idNat' => 18, 'nationalite' => 'Grec(que)'],
            ['idNat' => 19, 'nationalite' => 'Vietnamien(ne)'],
            ['idNat' => 20, 'nationalite' => 'Allemand(e)'],
        ]);
    }
}
