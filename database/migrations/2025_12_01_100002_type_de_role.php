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
        Schema::create('type_de_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idRolPer');
            $table->string('libRol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SChema::dropIfExists('type_de_role');
    }
};
