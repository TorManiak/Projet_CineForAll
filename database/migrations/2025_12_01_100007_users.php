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
        Schema::create('users', function(Blueprint $table) {
            $table->id('idUti');
            $table->string('nomUti');
            $table->string('preUti');
            $table->string ('mdpUti');
            $table->dateTime('datInsUti');
            $table->string('mailUti');
            $table->foreignId('idRolUti')
                ->references('idrolUti')
                ->on('role_utilisateur');
                //->cascadeOnDelete();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
