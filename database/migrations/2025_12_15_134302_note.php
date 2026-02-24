<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('note', function (Blueprint $table) {
            $table->unsignedBigInteger('idFil');
            $table->unsignedBigInteger('idUti');
            $table->primary(['idFil', 'idUti']);
            $table->foreign('idFil')
                ->references('idFil')
                ->on('film');
            $table->foreign('idUti')
                ->references('idUti')
                ->on('users');
            //Un utilistaue rne peux mettre qu'une note a un film
            $table->unique(['idFil', 'idUti']);
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('note', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->dropForeign(['idFil']);
            $table->dropForeign(['idUti']);

            $table->dropUnique(['idFil', 'idUti']);
        });
    }
};
