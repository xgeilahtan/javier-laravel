<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profissional', function (Blueprint $table) {
            $table->id('idProfissional');
            $table->string('cpf', 14)->unique();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('especialidade')->nullable()->comment('Campo descritivo para exibir no perfil');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profissional');
    }
};