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
        Schema::create('avoir', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('idFil');
            $table->unsignedBigInteger('idGen');
            $table->primary(['idFil', 'idGen']);
            $table->foreign('idFil')
                ->references('idFil')
                ->on('film');
            $table->foreign('idGen')
                ->references('idGen')
                ->on('genre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avoir');
    }
};
