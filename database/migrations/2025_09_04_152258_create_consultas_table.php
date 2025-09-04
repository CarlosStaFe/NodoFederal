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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->string('tipo');
            $table->string('cuit');
            $table->string('apelynombres');
            $table->dateTime('fecha');
            $table->unsignedBigInteger('nodo_id');
            $table->foreign('nodo_id')->references('id')->on('nodos')->onDelete('cascade');
            $table->unsignedBigInteger('socio_id');
            $table->foreign('socio_id')->references('id')->on('socios')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
