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
