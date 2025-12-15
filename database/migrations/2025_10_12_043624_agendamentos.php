<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id('id_agendamento');
            $table->foreignId('id_cliente')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_profissional')->constrained('users', 'id_nivel')->onDelete('cascade');
            $table->foreignId('id_servico')->constrained(table: 'servicos', column: 'id_servico')->onDelete('cascade');
            $table->dateTime('data_hora_inicio');
            $table->dateTime('data_hora_fim');
            $table->text('observacoes')->nullable();
            $table->enum('status', ['Confirmado', 'Concluido', 'Cancelado'])->default('Confirmado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};