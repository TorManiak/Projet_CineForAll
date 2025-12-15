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
            $table->unsignedBigInteger('idFil');
            $table->unsignedBigInteger('idPer');
            $table->unsignedBigInteger('idRolPer');
            $table->primary(['idFil', 'idPer', 'idRolPer']);
            $table->foreign('idFil')->references('idFil')->on('film');
            $table->foreign('idPer')->references('idPer')->on('personnalite');
            $table->foreign('idRolPer')->references('idRolPer')->on('type_de_role');
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
