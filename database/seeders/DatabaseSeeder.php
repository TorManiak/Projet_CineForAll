<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UtilisateurSeeder::class,
            GenreSeeder::class,
            FilmSeeder::class,
            TypeDeRoleSeeder::class,
            CinemaSeeder::class,
            PersonnaliteSeeder::class,
        ]);
    }
}

