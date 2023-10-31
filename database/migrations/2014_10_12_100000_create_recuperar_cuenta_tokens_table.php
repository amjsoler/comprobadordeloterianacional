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
        Schema::create('recuperar_cuenta_tokens', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("usuario")->unique();
            $table->foreign("usuario")->references("id")->on("users")->restrictOnDelete();

            $table->string("token");
            $table->timestamp("valido_hasta");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recuperar_cuenta_tokens');
    }
};
