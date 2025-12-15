<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveis', function (Blueprint $table) {
            $table->id('id_nivel');
            $table->string('descricao', 50)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveis');
    }
};