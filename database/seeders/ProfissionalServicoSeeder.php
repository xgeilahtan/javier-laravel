<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Servico;

class ProfissionalServicoSeeder extends Seeder
{
    public function run(): void
    {
        // Pega os USUÁRIOS que são profissionais
        $joana = User::where('email', 'joana@salao.com')->first();
        $carlos = User::where('email', 'carlos@salao.com')->first();

        // Pega os serviços
        $corte = Servico::where('nome', 'Corte Feminino')->first();
        $coloracao = Servico::where('nome', 'Coloração')->first();
        $progressiva = Servico::where('nome', 'Escova Progressiva')->first();
        
        // Usa o relacionamento 'servicos()' definido no model User para criar os vínculos
        if ($joana && $corte) {
            $joana->servicos()->attach($corte->id_servico);
        }
        if ($joana && $coloracao) {
            $joana->servicos()->attach($coloracao->id_servico);
        }
        if ($carlos && $corte) {
            $carlos->servicos()->attach($corte->id_servico);
        }
        if ($carlos && $progressiva) {
            $carlos->servicos()->attach($progressiva->id_servico);
        }
    }
}