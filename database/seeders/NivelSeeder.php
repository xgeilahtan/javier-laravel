<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('niveis')->insert([
            ['id_nivel' => 1, 'descricao' => 'cliente'],
            ['id_nivel' => 2, 'descricao' => 'profissional'],
            ['id_nivel' => 3, 'descricao' => 'admin'],
        ]);
    }
}