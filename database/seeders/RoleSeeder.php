<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role_utilisateur')->insert([
            ['idRolUti' => 1, 'libRolUti' => 'Admin'],
            ['idRolUti' => 2, 'libRolUti' => 'Utilisateur'],
            ['idRolUti' => 3, 'libRolUti' => 'Client'],
        ]);
    }
}

