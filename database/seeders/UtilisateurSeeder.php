<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nomUti' => 'Admin',
                'preUti' => 'Admin',
                'mdpUti' => ('1234'),/*Hash:make*/
                'datInsUti' => now(),
                'mailUti' => 'admin@admin.com',
                'idrolUti' => 1
            ],
            [
                'nomUti' => 'AdminTest',
                'preUti' => 'AdminTest',
                'mdpUti' => ('1234'),/*Hash:make*/
                'datInsUti' => now(),
                'mailUti' => 'adminTest@admin.com',
                'idrolUti' => 2
            ],
            [
                'nomUti' => 'GOUDET',
                'preUti' => 'Magalie',
                'mdpUti' => ('MagalieAvecUnECMieux'),/*Hash:make*/
                'datInsUti' => now(),
                'mailUti' => 'mg@client.com',
                'idRolUti' => 2
            ]
        ]);
    }
}

