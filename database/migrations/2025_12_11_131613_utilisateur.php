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
        Schema::create('utilisateur', function(Blueprint $table) {
            $table->id('idUti');
            $table->string('nomUti');
            $table->string('preUti');
            $table->string ('mdpUti');
            $table->dateTime('datInsUti');
            $table->string('mailUti');
            $table->foreignId('idRolUti')
                ->constrained('role_utilisateur')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
    }
};
