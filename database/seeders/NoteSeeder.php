<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nette\Schema\Schema;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("note")->insert([
            ['idFil' => 1,
            'idUti' => 3,
            'note'=> 2.3],
            ['idFil' => 2,
            'idUti' => 3,
            'note' => 4.2]
        ]);
    }
}
