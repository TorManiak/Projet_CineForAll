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
            $table->foreign('idFil')->references('idFil')->on('film');
            $table->foreign('idUti')->references('idUti')->on('utilisateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note');
    }
};
