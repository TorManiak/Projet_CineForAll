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
            ClassificationSeeder::class,
            LangueSeeder::class,
            NationaliteSeeder::class,
            UtilisateurSeeder::class,
            GenreSeeder::class,
            FilmSeeder::class,
            FilmLangueSeeder::class,
            TypeDeRoleSeeder::class,
            CinemaSeeder::class,
            PersonnaliteSeeder::class,
            NoteSeeder::class,
            JouerSeeder::class,
            SeanceSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}

