<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->id('id_servico');
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->integer('duracao_minutos');
            $table->decimal('preco', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};