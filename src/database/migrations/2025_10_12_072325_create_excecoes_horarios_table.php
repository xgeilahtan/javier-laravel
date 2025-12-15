<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excecoes_horario', function (Blueprint $table) {
            $table->id();
            // Se for NULL, a exceção se aplica ao salão inteiro (ex: feriado)
            $table->foreignId('idProfissional')->nullable()->constrained('profissional', 'idProfissional')->onDelete('cascade');
            $table->date('data');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->boolean('folga')->default(false)->comment('True se for um dia de folga total');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excecoes_horario');
    }
};