<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonnaliteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('personnalite')->insert([

            [
                'nomPer' => 'DiCaprio',
                'prePer' => 'Leonardo',
                'datNaiPer' => '1974-11-11 00:00:00',
                'desPer' => 'Acteur engagé dans la protection de l’environnement.',
                'idNat' => 1
            ],

            [
                'nomPer' => 'Nolan',
                'prePer' => 'Christopher',
                'datNaiPer' => '1970-07-30 00:00:00',
                'desPer' => 'Spécialiste des films à narration complexe.',
                'idNat' => 1
            ],

            [
                'nomPer' => 'Sy',
                'prePer' => 'Omar',
                'datNaiPer' => '1978-01-20 00:00:00',
                'desPer' => 'Acteur populaire en France et à l’international.',
                'idNat' => 1
            ],

            [
                'nomPer' => 'Zimmer',
                'prePer' => 'Hans',
                'datNaiPer' => '1957-09-12 00:00:00',
                'desPer' => 'Créateur de bandes originales emblématiques.',
                'idNat' => 1
            ],

            [
                'nomPer' => 'Feige',
                'prePer' => 'Kevin',
                'datNaiPer' => '1973-06-02 00:00:00',
                'desPer' => 'Producteur majeur de l’univers Marvel.',
                'idNat' => 1
            ],

        ]);
    }
}
