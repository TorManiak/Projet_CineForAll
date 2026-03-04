<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classification')->insert([
            [
                'classification' => 'Tout publique'
            ],
            [
                'classification' => 'Moins 12 ans'
            ],
            [
                'classification' => 'Moins 16 ans'
            ],
            [
                'classification' => 'Moins 18 ans'
            ],
        ]);
    }
}
