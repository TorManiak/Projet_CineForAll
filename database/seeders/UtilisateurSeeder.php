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
        DB::table('utilisateurs')->insert([
            [
                'nomUti' => 'Admin',
                'preUti' => 'Admin',
                'mdpUti' => Hash::make('1234'),
                'datInsUti' => now(),
                'mailUti' => 'admin@admin.com'
            ],
            [
                'nomUti' => 'AdminTest',
                'preUti' => 'AdminTest',
                'mdpUti' => Hash::make('1234'),
                'datInsUti' => now(),
                'mailUti' => 'adminTest@admin.com'
            ],
            [
                'nomUti' => 'GOUDET',
                'preUti' => 'Magalie',
                'mdpUti' => Hash::make('MagalieAvecUnECMieux'),
                'datInsUti' => now(),
                'mailUti' => 'mg@client.com'
            ]
        ]);
    }
}

