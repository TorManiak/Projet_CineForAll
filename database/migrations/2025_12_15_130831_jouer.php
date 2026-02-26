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
        Schema::create('jouer', function (Blueprint $table) {
            $table->id('idJouer');
            $table->boolean('realisateur');
            $table->unsignedBigInteger('idFil');
            $table->unsignedBigInteger('idPer');
            $table->unsignedBigInteger('idRolPer');
            //$table->primary(['idFil', 'idPer', 'idRolPer']); les 3 lignes au dessus les mettent en cle primaire ?
            $table->foreign('idFil')
                ->references('idFil')
                ->on('film');
            $table->foreign('idPer')
                ->references('idPer')
                ->on('personnalite');
            $table->foreign('idRolPer')
                ->references('idRolPer')
                ->on('type_de_role');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jouer');
    }
};
