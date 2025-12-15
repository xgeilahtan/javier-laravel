<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagem_servicos', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->foreignId('id_servico')->constrained('servicos', 'id_servico')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagem_servicos');
    }
};