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
        Schema::create('garantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operacion_id')->constrained('operaciones')->onDelete('cascade');
            $table->string('cuit', 11);
            $table->string('tipodoc', 5);
            $table->string('sexo', 1);
            $table->string('documento', 20);
            $table->string('apelnombres', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantes');
    }
};
