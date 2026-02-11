<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CinemaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cinema')->insert([
            [
                'nomCin' => 'CineForAll Paris',
                'adrCin' => '12 Rue de Rivoli',
                'vilCin' => 'Paris',
                'cpCin' => '75001',
                'maiCin' => 'paris@cineforall.fr',
                'telCin' => '0142000001'
            ],
            [
                'nomCin' => 'CineForAll Lyon',
                'adrCin' => '25 Avenue Jean Jaurès',
                'vilCin' => 'Lyon',
                'cpCin' => '69007',
                'maiCin' => 'lyon@cineforall.fr',
                'telCin' => '0472000002'
            ],
            [
                'nomCin' => 'CineForAll Marseille',
                'adrCin' => '8 Boulevard Longchamp',
                'vilCin' => 'Marseille',
                'cpCin' => '13001',
                'maiCin' => 'marseille@cineforall.fr',
                'telCin' => '0491000003'
            ],
            [
                'nomCin' => 'CineForAll Lille',
                'adrCin' => '5 Grand Place',
                'vilCin' => 'Lille',
                'cpCin' => '59000',
                'maiCin' => 'lille@cineforall.fr',
                'telCin' => '0320000004'
            ]
        ]);
    }
}
