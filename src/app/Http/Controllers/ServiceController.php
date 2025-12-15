<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index()
    {
        $servicos = Servico::with(['profissionais', 'imagens'])->get();

        $profissionais = User::whereHas('profissional')->orderBy('name')->get();

        $categorias = Servico::distinct()->orderBy('categoria')->pluck('categoria');

        return view('pages.servico', [
            'servicos'      => $servicos,
            'profissionais' => $profissionais,
            'categorias'    => $categorias,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'duracao_minutos' => 'required|integer|min:1',
            'preco' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:255',
        ],
        [
            'nome.required' => 'O campo nome é obrigatório.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'duracao_minutos.required' => 'O campo duração é obrigatório.',
            'preco.required' => 'O campo preço é obrigatório.',
            'categoria.required' => 'O campo categoria é obrigatório.',
        ]);

        $servico = Servico::create($validatedData);

        return response()->json(['message' => 'Serviço criado com sucesso', 'servico' => $servico], 201);
    }

    public function update(Request $request, $id)
    {
        $servico = Servico::findOrFail($id);

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'duracao_minutos' => 'required|integer|min:1',
            'preco' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:255',
        ],
        [
            'nome.required' => 'O campo nome é obrigatório.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'duracao_minutos.required' => 'O campo duração é obrigatório.',
            'preco.required' => 'O campo preço é obrigatório.',
            'categoria.required' => 'O campo categoria é obrigatório.',
        ]);

        $servico->update($validatedData);

        return response()->json(['message' => 'Serviço atualizado com sucesso', 'servico' => $servico], 200);
    }

    public function destroy($id)
    {
        $servico = Servico::findOrFail($id);
        $servico->delete();

        return response()->json(['message' => 'Serviço excluído com sucesso'], 200);
    }

    public function data()
    {
        try{
            $servicos = Servico::select(['id_servico', 'nome', 'descricao', 'duracao_minutos', 'preco', 'categoria'])->get();
            return response()->json(['data' => $servicos]);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar serviços: " . $e->getMessage());
            return response()->json(['message' => 'Erro ao buscar serviços: ' . $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $term = $request->input('q');

        $query = Servico::query();

        if ($term) {
            $query->where('nome', 'LIKE', "%{$term}%");
        }

        $servicos = $query->select('id_servico', 'nome', 'preco') // Seleciona apenas o necessário
                        ->limit(20)
                        ->get();

        $results = $servicos->map(function ($servico) {
            return [
                'id' => $servico->id_servico,
                'text' => $servico->nome . ' (R$ ' . number_format($servico->preco, 2, ',', '.') . ')'
            ];
        });

        return response()->json($results);
    }
}
