<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('profissionais_servicos', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('id_servico')->constrained(table: 'servicos', column: 'id_servico')->onDelete('cascade');
        $table->primary(['user_id', 'id_servico']);
    });
}

    public function down(): void
    {
        Schema::dropIfExists('profissionais_servicos');
    }
};