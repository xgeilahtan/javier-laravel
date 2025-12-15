<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('idCliente');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->text('observacoes')->nullable()->comment('Informações importantes sobre o cliente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};