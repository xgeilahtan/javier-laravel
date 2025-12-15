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
            // CORREÇÃO 1: Aponta para a tabela 'clientes' e a coluna 'idCliente'
            // Certifique-se que sua tabela se chama 'clientes' no banco.
            $table->foreignId('id_cliente')
                  ->constrained(table: 'cliente', column: 'idCliente')
                  ->onDelete('cascade');

            // CORREÇÃO 2: Aponta para a tabela 'profissional' e coluna 'idProfissional'
            // O erro do 'id_nivel' estava aqui.
            $table->foreignId('id_profissional')
                  ->constrained(table: 'profissional', column: 'idProfissional')
                  ->onDelete('cascade');

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
