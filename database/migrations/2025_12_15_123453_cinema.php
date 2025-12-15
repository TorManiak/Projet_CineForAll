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
        SCHEMA::create('cinema', function (Blueprint $table) {
            $table->id();
            $table->string('nomCin');
            $table->string('adrCin');
            $table->string('vilCin');
            $table->string('cpCin');
            $table->string('maiCin');
            $table->string('telCin');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cinema');
    }
};
