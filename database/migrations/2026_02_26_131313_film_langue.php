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
        Schema::create('film_langue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idFil');
            $table->unsignedBigInteger('idLan');

            $table->foreign('idFil')
                ->references('idFil')
                ->on('film');
                //->onDelete('cascade'); pas besoin mtn
            $table->foreign('idLan')
                ->references('idLan')
                ->on('langue');
                //->onDelete('cascade'); pas besoin mtn

            $table->unique(['idFil', 'idLan']); // Évite les doublons
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
