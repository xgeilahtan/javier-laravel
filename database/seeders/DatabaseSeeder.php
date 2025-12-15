<?php

namespace Database\Seeders;

use App\Models\Administrador;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\ExcecaoHorario;
use App\Models\ImagemServico;
use App\Models\Nivel;
use App\Models\Profissional;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Desativa a verificação de chaves estrangeiras para permitir o truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Agendamento::truncate();
        ExcecaoHorario::truncate();
        ImagemServico::truncate();
        DB::table('profissionais_servicos')->truncate(); // Certifique-se que o nome da tabela pivo esta certo
        Servico::truncate();
        Profissional::truncate();
        Cliente::truncate();
        Administrador::truncate();
        User::truncate();
        Nivel::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $password = Hash::make('12345678');

        // --- 1. NÍVEIS ---
        $nivelAdmin = Nivel::create(['descricao' => 'Administrador']);
        $nivelProfissional = Nivel::create(['descricao' => 'Profissional']);
        $nivelCliente = Nivel::create(['descricao' => 'Cliente']);

        // --- 2. USUÁRIOS E PERFIS ---

        // ADMIN
        $userAdmin = User::create([
            'name' => 'Javier (Admin)', 'email' => 'admin@javier.com', 'password' => $password,
            'id_nivel' => $nivelAdmin->id_nivel, 'telefone' => '11999999999', 'ativo' => 1,
        ]);
        Administrador::create(['user_id' => $userAdmin->id]);

        // JÂNIO (Profissional)
        $userJanio = User::create([
            'name' => 'Jânio', 'email' => 'janio@javier.com', 'password' => $password,
            'id_nivel' => $nivelProfissional->id_nivel, 'telefone' => '11999999901', 'ativo' => 1,
        ]);
        $profJanio = Profissional::create([
            'user_id' => $userJanio->id, 'cpf' => '00000000001', 'especialidade' => 'Cortes Modernos e Clássicos'
        ]);

        // DIVINA (Profissional)
        $userDivina = User::create([
            'name' => 'Divina', 'email' => 'divina@javier.com', 'password' => $password,
            'id_nivel' => $nivelProfissional->id_nivel, 'telefone' => '11999999902', 'ativo' => 1,
        ]);
        $profDivina = Profissional::create([
            'user_id' => $userDivina->id, 'cpf' => '00000000002', 'especialidade' => 'Esteticista e Manicure'
        ]);

        // NATHALIE (Profissional)
        $userNathalie = User::create([
            'name' => 'Nathalie', 'email' => 'nathalie@javier.com', 'password' => $password,
            'id_nivel' => $nivelProfissional->id_nivel, 'telefone' => '11999999903', 'ativo' => 1,
        ]);
        $profNathalie = Profissional::create([
            'user_id' => $userNathalie->id, 'cpf' => '00000000003', 'especialidade' => 'Colorista Especializada'
        ]);

        // ANA (Cliente)
        $userCliente = User::create([
            'name' => 'Ana Cliente', 'email' => 'ana@cliente.com', 'password' => $password,
            'id_nivel' => $nivelCliente->id_nivel, 'telefone' => '11888888888', 'ativo' => 1,
        ]);
        // CORREÇÃO AQUI: Capturei o perfil do cliente numa variável
        $perfilClienteAna = Cliente::create(['user_id' => $userCliente->id, 'observacoes' => 'Cliente de teste para agendamentos.']);

        // --- 3. SERVIÇOS ---
        $corte = Servico::create(['nome' => 'Corte de Cabelo', 'descricao' => 'Corte moderno ou clássico.', 'duracao_minutos' => 60, 'preco' => 120.00, 'categoria' => 'Cabelo']);
        $coloracao = Servico::create(['nome' => 'Coloração Global', 'descricao' => 'Aplicação de cor uniforme.', 'duracao_minutos' => 120, 'preco' => 280.00, 'categoria' => 'Cabelo']);
        $manicure = Servico::create(['nome' => 'Manicure & Pedicure', 'descricao' => 'Cuidado completo.', 'duracao_minutos' => 90, 'preco' => 70.00, 'categoria' => 'Unhas']);
        $maquiagem = Servico::create(['nome' => 'Maquiagem Profissional', 'descricao' => 'Maquiagem para eventos.', 'duracao_minutos' => 75, 'preco' => 150.00, 'categoria' => 'Maquiagem']);
        $depilacao = Servico::create(['nome' => 'Depilação (Virilha)', 'descricao' => 'Depilação com cera.', 'duracao_minutos' => 30, 'preco' => 50.00, 'categoria' => 'Depilação']);

        // --- 4. IMAGENS DE SERVIÇOS ---
        $galeriaImagens = [
            $corte->id_servico => [
                'images/servicos/cortecabelo.jpg',
            ],
            $coloracao->id_servico => [
                'images/servicos/coloracao.jpg',
                'images/servicos/coloracao2.png',
                'images/servicos/coloracao3.png'
            ],
            $manicure->id_servico => [
                'images/servicos/manicure.webp',
                'images/servicos/pedicure.jpg',
                'images/servicos/pedicure1.jpg'
            ],
            $maquiagem->id_servico => [
                'images/noimage.jpg'
            ],
            $depilacao->id_servico => [
                'images/servicos/depilacao.webp',
                'images/servicos/depilacao1.png'
            ]
        ];

        foreach ($galeriaImagens as $idServico => $caminhos) {
            foreach ($caminhos as $path) {
                ImagemServico::create([
                    'id_servico' => $idServico, 
                    'image_path' => $path
                ]);
            }
        }

        // --- 5. LIGAÇÃO PROFISSIONAL <-> SERVIÇO ---
        // IMPORTANTE: Se a relação for no User, mantenha assim. Se for no model Profissional, mude para $profJanio->servicos()...
        // Vou assumir que você definiu a relação no model User como estava antes.
        $userJanio->servicos()->attach([$corte->id_servico, $coloracao->id_servico]);
        $userDivina->servicos()->attach([$manicure->id_servico, $depilacao->id_servico]);
        $userNathalie->servicos()->attach([$coloracao->id_servico, $maquiagem->id_servico]);

        // --- 6. AGENDAMENTOS (AQUI ESTAVA O ERRO) ---
        Agendamento::create([
            // ERRADO ANTES: 'id_cliente' => $userCliente->id,
            // CORRETO AGORA: Usa o idCliente do perfil
            'id_cliente' => $perfilClienteAna->idCliente,

            // ERRADO ANTES: 'id_profissional' => $userJanio->id,
            // CORRETO AGORA: Usa o idProfissional do perfil
            'id_profissional' => $profJanio->idProfissional,

            'id_servico' => $corte->id_servico,
            'data_hora_inicio' => '2025-10-20 10:00:00',
            'data_hora_fim' => '2025-10-20 11:00:00',
        ]);

        Agendamento::create([
            'id_cliente' => $perfilClienteAna->idCliente, // ID Correto
            'id_profissional' => $profDivina->idProfissional, // ID Correto
            'id_servico' => $manicure->id_servico,
            'data_hora_inicio' => '2025-10-21 14:00:00',
            'data_hora_fim' => '2025-10-21 15:30:00',
        ]);

        // --- 7. EXCEÇÕES ---
        ExcecaoHorario::create([
            'idProfissional' => $profJanio->idProfissional,
            'data' => '2025-12-25',
            'folga' => true,
            'observacao' => 'Natal',
        ]);
        ExcecaoHorario::create([
            'idProfissional' => $profDivina->idProfissional,
            'data' => '2025-11-10',
            'hora_inicio' => '09:00:00',
            'hora_fim' => '12:00:00',
            'folga' => false,
            'observacao' => 'Atendimento externo. Volta às 12h.',
        ]);
    }
}
