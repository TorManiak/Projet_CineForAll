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
                'natPer' => 'Américaine',
                'bibPer' => 'Acteur américain connu pour Titanic, Inception et The Revenant.',
                'desPer' => 'Acteur engagé dans la protection de l’environnement.'
            ],

            [
                'nomPer' => 'Nolan',
                'prePer' => 'Christopher',
                'datNaiPer' => '1970-07-30 00:00:00',
                'natPer' => 'Britannique',
                'bibPer' => 'Réalisateur de films à succès comme Inception et Interstellar.',
                'desPer' => 'Spécialiste des films à narration complexe.'
            ],

            [
                'nomPer' => 'Sy',
                'prePer' => 'Omar',
                'datNaiPer' => '1978-01-20 00:00:00',
                'natPer' => 'Française',
                'bibPer' => 'Acteur français révélé par Intouchables.',
                'desPer' => 'Acteur populaire en France et à l’international.'
            ],

            [
                'nomPer' => 'Zimmer',
                'prePer' => 'Hans',
                'datNaiPer' => '1957-09-12 00:00:00',
                'natPer' => 'Allemande',
                'bibPer' => 'Compositeur de musiques de films célèbres.',
                'desPer' => 'Créateur de bandes originales emblématiques.'
            ],

            [
                'nomPer' => 'Feige',
                'prePer' => 'Kevin',
                'datNaiPer' => '1973-06-02 00:00:00',
                'natPer' => 'Américaine',
                'bibPer' => 'Producteur et président de Marvel Studios.',
                'desPer' => 'Producteur majeur de l’univers Marvel.'
            ],

        ]);
    }
}
