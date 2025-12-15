<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Administrador;
use App\Models\Profissional; // <-- IMPORTE O MODEL PROFISSIONAL
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@localhost'],
                [
                    'name' => 'Administrador',
                    'password' => Hash::make('senha'),
                    'id_nivel' => 3,
                    'dt_nasc' => '1990-01-01',
                    'email_verified_at' => now(),
                    'ativo' => 'S',
                ]
            );

            Profissional::firstOrCreate(
                ['user_id' => $adminUser->id],
                [
                    'cpf' => '00000000000',
                    'especialidade' => 'Administrador do Sistema'
                ]
            );

            Administrador::firstOrCreate(
                ['user_id' => $adminUser->id]
            );
        });
    }
}