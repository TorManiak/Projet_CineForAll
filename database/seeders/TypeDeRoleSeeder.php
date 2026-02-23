<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\DB;

class TypeDeRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_de_role')->insert([
            ['libRol' => 'realisateur'],
            ['libRol' => 'acteur'],
            ['libRol' => 'producteur'],
            ['libRol' => 'inge-son'],
            ['libRol' => 'co-producteur'],
            ['libRol' => 'co-realisateur'],
    ]);
    }
}
