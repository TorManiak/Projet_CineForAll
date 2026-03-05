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
        Schema::create('seance', function (Blueprint $table) {
            $table->id('idSea');
            $table->unsignedBigInteger("idFil");
            $table->foreign('idFil')
                ->references('idFil')
                ->on('film');
            $table->unsignedBigInteger("idCin");
            $table->foreign('idCin')
                ->references('idCin')
                ->on('cinema');
            $table->dateTime("datHeuSea");
            $table->decimal("priSea",4,2);
            $table->unsignedBigInteger("idSal");
            $table->foreign('idSal')
                ->references('idSal')
                ->on('salle');
            $table->unsignedBigInteger("idLan");
            $table->foreign('idLan')
                ->references('idLan')
                ->on('langue');
            $table->boolean('malVoyEnt');
            $table->foreign('malVoyEnt')
                ->references('malVoyEnt')
                ->on('film');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seance');
    }
};
