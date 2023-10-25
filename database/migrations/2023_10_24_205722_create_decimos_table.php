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
        Schema::create('decimos', function (Blueprint $table) {
            $table->id();
            $table->string("numero");
            $table->string("reintegro");
            $table->string("serie")->nullable();
            $table->string("fraccion")->nullable();
            $table->integer("cantidad")->default(1);

            $table->unsignedBigInteger("usuario");
            $table->foreign("usuario")->on("users")->references("id");

            $table->unsignedBigInteger("sorteo");
            $table->foreign("sorteo")->on("sorteos")->references("id");

            $table->decimal("premio")->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decimos');
    }
};
