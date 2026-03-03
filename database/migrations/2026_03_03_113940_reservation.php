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
        Schema::create('reservation', function (Blueprint $table) {
            $table->id('idRes');
            $table->unsignedBigInteger('idUti');
            $table->foreign('idUti')
                ->references('idUti')
                ->on('users');
            $table->unsignedBigInteger("idSea");
            $table->foreign('idSea')
                ->references('idSea')
                ->on('seance');
            $table->integer('nbPlaces');
            $table->dateTime('datRes');
            $table->enum('status',['en attente', 'confirmée', 'annulée'])
                ->default('en attente');
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
