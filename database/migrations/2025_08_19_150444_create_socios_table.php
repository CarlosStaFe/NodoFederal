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
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 10)->unique();

            $table->unsignedBigInteger('nodo_id');
            $table->foreign('nodo_id')->references('id')->on('nodos')->nullable();

            $table->string('clase', 30);
            $table->string('razon_social', 100);
            $table->string('domicilio', 100);

            $table->unsignedBigInteger('cod_postal_id');
            $table->foreign('cod_postal_id')->references('id')->on('localidades')->nullable();

            $table->string('telefono', 50)->nullable();
            $table->string('email', 80)->nullable();
            $table->string('cuit', 11)->nullable();
            $table->string('tipo', 20)->nullable();
            $table->string('estado', 20)->default('Activo');
            $table->string('observacion', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
