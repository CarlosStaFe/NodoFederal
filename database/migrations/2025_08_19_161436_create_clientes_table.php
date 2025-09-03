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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipodoc', 5);
            $table->integer('documento');
            $table->string('sexo', 1);
            $table->string('cuit', 11)->unique();
            $table->string('apelnombres', 50);
            $table->date('nacimiento')->nullable();
            $table->string('nacionalidad', 30)->nullable();
            $table->string('domicilio', 100);

            $table->unsignedBigInteger('cod_postal_id');
            $table->foreign('cod_postal_id')->references('id')->on('localidades')->nullable();

            $table->string('telefono', 50);
            $table->string('email', 80)->nullable();
            $table->string('estado', 20)->default('Activo');
            $table->date('fechaestado')->nullable();
            $table->string('observacion', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
