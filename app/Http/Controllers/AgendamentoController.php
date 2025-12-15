<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\Profissional;
use App\Models\Agendamento;
use App\Models\Cliente;

// REMOVIDO: use App\Models\HorarioPadraoProfissional;
use App\Models\ExcecaoHorario;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
    public function create()
    {
        $servicos = Servico::all();

        // AGORA: Passa a variável 'servicos' para a view.
        return view('pages.agendamento', compact('servicos'));
    }

    public function getProfissionaisPorServico(Servico $servico)
    {
        // 1. Busca os USUÁRIOS que fazem este serviço e já carrega o perfil 'profissional' de cada um.
        $usuarios_profissionais = $servico->profissionais()->with('profissional')->get();

        // 2. Formata a resposta para o JavaScript, pegando os dados corretos de cada modelo.
        $resposta = $usuarios_profissionais->map(function ($usuario) {
            // Verifica se o perfil profissional existe para evitar erros
            if ($usuario->profissional) {
                return [
                    // O ID que o front-end precisa é o idProfissional
                    'idProfissional' => $usuario->profissional->idProfissional,

                    // O nome do profissional está no modelo User
                    'user' => [
                        'name' => $usuario->name
                    ]
                ];
            }
            return null;
        })->filter(); // O ->filter() remove quaisquer resultados nulos da lista.

        return response()->json($resposta);
    }

    public function getHorariosDisponiveis(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|date_format:Y-m-d',
            'profissional_id' => 'required|exists:profissional,idProfissional',
            'servico_id' => 'required|exists:servicos,id_servico',
        ]);

        $data = Carbon::parse($validated['data']);
        $profissional = Profissional::find($validated['profissional_id']);
        $servico = Servico::find($validated['servico_id']);
        $duracaoServico = $servico->duracao_minutos;

        // Tenta encontrar uma exceção para o dia
        $excecao = ExcecaoHorario::where('idProfissional', $profissional->idProfissional)
            ->where('data', $data->toDateString())->first();

        $inicioExpediente = null;
        $fimExpediente = null;

        if ($excecao) {
            // Se encontrou uma exceção
            if ($excecao->folga) {
                return response()->json([]); // É um dia de folga, retorna vazio.
            }
            // Usa o horário da exceção como o expediente do dia
            $inicioExpediente = Carbon::parse($data->toDateString() . ' ' . $excecao->hora_inicio);
            $fimExpediente = Carbon::parse($data->toDateString() . ' ' . $excecao->hora_fim);
        } else {
            // Se NÃO encontrou exceção, usa o horário PADRÃO
            $inicioExpediente = Carbon::parse($data->toDateString() . ' 08:00:00');
            $fimExpediente = Carbon::parse($data->toDateString() . ' 18:00:00');
        }

        // --- A PARTIR DAQUI, A LÓGICA É A MESMA ---

        // Busca agendamentos existentes para subtrair
        $agendamentosDoDia = Agendamento::where('id_profissional', $profissional->idProfissional)
            ->whereDate('data_hora_inicio', $data->toDateString())
            ->where('status', '!=', 'Cancelado')->get();

        // Calcula os horários disponíveis
        $horariosDisponiveis = [];
        $horarioAtual = $inicioExpediente->copy();
        $intervalo = 15; // Define o intervalo de verificação (ex: de 15 em 15 minutos)

        while ($horarioAtual->copy()->addMinutes($duracaoServico)->lte($fimExpediente)) {
            $estaOcupado = false;
            foreach ($agendamentosDoDia as $agendamento) {
                $inicioAgendado = Carbon::parse($agendamento->data_hora_inicio);
                $fimAgendado = Carbon::parse($agendamento->data_hora_fim);
                // Verifica colisão de horários
                if ($horarioAtual->copy()->addMinutes($duracaoServico)->isAfter($inicioAgendado) && $horarioAtual->isBefore($fimAgendado)) {
                    $estaOcupado = true;
                    break;
                }
            }
            if (!$estaOcupado) {
                $horariosDisponiveis[] = $horarioAtual->format('H:i');
            }
            $horarioAtual->addMinutes($intervalo);
        }
        return response()->json($horariosDisponiveis);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'servico_id' => 'required|exists:servicos,id_servico',
            'profissional_id' => 'required|exists:profissional,idProfissional',
            'data' => 'required|date_format:Y-m-d',
            'horario' => 'required|date_format:H:i',
        ]);

        $user = Auth::user();

        if (!$user || !$user->cliente) {
            return back()->with('error', 'Seu usuário não tem permissão para realizar agendamentos.');
        }

        $servico = Servico::find($validated['servico_id']);
        if (!$servico) {
            return back()->with('error', 'Serviço não encontrado.');
        }


        $idCliente = Cliente::where('user_id', $user->id)->value('idCliente');

        $inicio = Carbon::parse($validated['data'] . ' ' . $validated['horario']);

        $fim = $inicio->copy()->addMinutes($servico->duracao_minutos);

        $agendamento = new Agendamento();
        $agendamento->id_cliente = $idCliente;
        $agendamento->id_profissional = $validated['profissional_id'];
        $agendamento->id_servico = $validated['servico_id'];
        $agendamento->data_hora_inicio = $inicio;
        $agendamento->data_hora_fim = $fim;
        $agendamento->status = 'Confirmado';
        $agendamento->save();

        return redirect()->route('home')->with('success', 'Agendamento confirmado com sucesso!');
    }

    public function data()
    {

        $user = Auth::user();

        $query = Agendamento::with(['cliente', 'profissional', 'servico']);

        if ($user->id_nivel == 3) {
            $query->where('id_cliente', $user->id);
        }
        elseif ($user->id_nivel == 2) {
            $query->where('id_profissional', $user->id);
        }

        $agendamentos = $query->get();

        $resultado = $agendamentos->map(function ($agendamento) {
            return [
                'idAgendamento' => $agendamento->id_agendamento,
                'cliente' => [
                    'name' => $agendamento->cliente?->name ?? 'Cliente N/A',
                ],
                'profissional' => [
                    'name' => $agendamento->profissional?->name ?? 'Profissional N/A',
                ],
                'servico' => [
                    'nome_servico' => $agendamento->servico?->nome ?? 'Serviço N/A',
                ],
                'data_hora_inicio' => $agendamento->data_hora_inicio,
                'data_hora_fim' => $agendamento->data_hora_fim,
                'observacoes' => $agendamento->observacoes,
            ];
        });

        return response()->json($resultado);
    }
}
