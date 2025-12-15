<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AtividadeController extends Controller
{
    // 1. Retorna a View
    public function index()
    {
        return view('pages.gestaoAtividades');
    }

    // 2. Retorna dados para o DataTable (Listagem dos vínculos)
    public function data()
    {
        $atividades = DB::table('profissionais_servicos') // 1. Nome corrigido conforme seu Model
            // 2. O Model diz que a chave é 'user_id', então ligamos com a tabela 'users' primeiro
            ->join('users', 'profissionais_servicos.user_id', '=', 'users.id')
            // 3. Ligamos com a tabela 'servicos'
            ->join('servicos', 'profissionais_servicos.id_servico', '=', 'servicos.id_servico')
            // 4. Precisamos da tabela 'profissional' SÓ para pegar o 'idProfissional' (usado no botão excluir)
            ->join('profissional', 'users.id', '=', 'profissional.user_id')
            ->select(
                'users.name as nome_profissional',
                'servicos.nome as nome_servico',
                'profissional.idProfissional', // O botão de delete do JS usa isso
                'servicos.id_servico',
                'users.id as user_id_pivo' // Caso precise depurar
            )
            ->get();

        return response()->json(['data' => $atividades]);
    }

    // 3. Cria o vínculo (Salvar)
    public function store(Request $request)
    {
        // O front envia 'id_profissional_usuario' (que é o idProfissional)
        // Mas a tabela pivo pede 'user_id'. Precisamos converter.

        $profissional = Profissional::find($request->id_profissional_usuario);

        if (!$profissional) {
            return response()->json(['success' => false, 'message' => 'Profissional não encontrado.'], 404);
        }

        // Verifica se já existe para não duplicar
        $existe = DB::table('profissionais_servicos')
            ->where('user_id', $profissional->user_id)
            ->where('id_servico', $request->id_servico)
            ->exists();

        if ($existe) {
            return response()->json(['success' => false, 'message' => 'Este profissional já realiza este serviço.']);
        }

        // Insere usando o user_id
        DB::table('profissionais_servicos')->insert([
            'user_id' => $profissional->user_id,
            'id_servico' => $request->id_servico
        ]);

        return response()->json(['success' => true, 'message' => 'Atividade vinculada com sucesso!']);
    }

    public function destroy(Request $request)
    {
        $profId = $request->input('id_profissional');
        $servId = $request->input('id_servico');

        // Busca o profissional para pegar o user_id correspondente
        $profissional = Profissional::find($profId);

        if ($profissional) {
            DB::table('profissionais_servicos')
                ->where('user_id', $profissional->user_id) // Usa user_id para deletar
                ->where('id_servico', $servId)
                ->delete();

            return response()->json(['success' => true, 'message' => 'Vínculo removido com sucesso!']);
        }

        return response()->json(['success' => false, 'message' => 'Erro ao remover vínculo.'], 500);
    }
}
