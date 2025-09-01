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
        Schema::create('operaciones', function (Blueprint $table) {
            $table->id();

            $table->integer('numero');

            $table->foreignId('id_cliente')->constrained('clientes')->onDelete('cascade');

            $table->string('estado_actual', 20);
            $table->date('fecha_estado');

            $table->foreignId('id_socio')->constrained('socios')->onDelete('cascade');

            $table->string('tipo', 20)->default('Solicitante');
            $table->date('fecha_operacion');
            $table->decimal('valor_cuota', 15, 2);
            $table->integer('cant_cuotas');
            $table->decimal('total', 15, 2);
            $table->date('fecha_cuota');
            $table->string('clase', 20)->default('Comercial');

            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operaciones');
    }
};
