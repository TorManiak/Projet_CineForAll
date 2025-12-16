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
            $table->id('idFil');
            $table->string('nomFil');
            $table->Time('datFil');
            $table->string('afiFil');
            $table->string('desFil');
            $table->string('typeFil');
            $table->boolean('malVoyEnt');
            $table->string('banAnn');
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
