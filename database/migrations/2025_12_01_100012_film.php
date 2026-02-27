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
        Schema::create('film', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idFil');
            $table->string('nomFil');
            $table->Time('datFil');
            $table->string('afiFil');
            $table->TEXT('desFil');
            $table->unsignedBigInteger('idGen');
            $table->foreign('idGen')
                ->references('idGen')
                ->on('genre');
            $table->boolean('malVoyEnt');
            $table->string('banAnn');
            $table->integer('annSor');
            $table->unsignedBigInteger('classification');
            $table->foreign('classification')
                ->references('idClass')//peut etre champ : idClass si ca ne marche pas
                ->on('classification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film');
    }
};
