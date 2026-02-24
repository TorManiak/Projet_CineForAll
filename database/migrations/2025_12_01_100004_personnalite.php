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
        Schema::create('personnalite', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idPer');
            $table->string('nomPer');
            $table->string('prePer');
            $table->DateTime('datNaiPer');
            $table->string('natPer');
            $table->string('bibPer');
            $table->string('desPer');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnalite');
    }
};
