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
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();

            $table->integer("numero");
            $table->integer("reintegro");
            $table->integer("serie")->nullable();
            $table->integer("fraccion")->nullable();

            $table->unsignedBigInteger("sorteo");
            $table->foreign("sorteo")->on("sorteos")->references("id");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};
