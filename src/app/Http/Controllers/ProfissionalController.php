<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // Para depuração

class ProfissionalController extends Controller
{
    /**
     * Exibe a página de gerenciamento de profissionais.
     * Esta função responde à rota /gestao_prof
     */
    public function index()
    {
        // Certifique-se de que o caminho 'pages.gestaoProf' está correto
        // (corresponde a 'resources/views/pages/gestaoProf.blade.php')
        return view('pages.gestaoProf');
    }

    // --- MÉTODOS PARA A API DO DATATABLE ---

    /**
     * 1. Retorna os dados para o DataTable (formato JSON)
     * Responde à rota: /api/gestao_prof/data
     */
    public function data()
    {
        try {
            $profissionais = Profissional::with('user')->get();
            return response()->json(['data' => $profissionais]);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar dados de profissionais: " . $e->getMessage());
            return response()->json(['data' => [], 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 2. Salva um novo profissional (via AJAX)
     * Responde à rota: /api/gestao_prof/store
     */
    public function storeApi(Request $request)
    {



        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Corrigido
            'password' => 'required|string|min:8',
            'telefone' => 'required|string|max:20',
            'dt_nasc' => 'required|date',
            'cep' => 'required|string|max:9',
            'endereco' => 'required|string|max:100',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'uf' => 'required|string|size:2',
            'cpf' => 'required|string|max:14|unique:profissional,cpf',
            'especialidade' => 'required|string|max:255',
            'genero' => 'required|in:M,F,O',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'email.unique' => 'Este email ja foi cadastrado.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'dt_nasc.required' => 'O campo data de nascimento é obrigatório.',
            'cep.required' => 'O campo cep é obrigatório.',
            'endereco.required' => 'O campo endereço é obrigatório.',
            'bairro.required' => 'O campo bairro é obrigatório.',
            'cidade.required' => 'O campo cidade é obrigatório.',
            'uf.required' => 'O campo uf é obrigatório.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já foi cadastrado.',
            'especialidade.required' => 'O campo especialidade é obrigatório.',
            'genero.required' => 'O campo sexo é obrigatório.',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                $user = User::create([
                    'name' => $validatedData['nome'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'telefone' => $validatedData['telefone'],
                    'dt_nasc' => $validatedData['dt_nasc'],
                    'cep' => $validatedData['cep'],
                    'endereco' => $validatedData['endereco'],
                    'bairro' => $validatedData['bairro'],
                    'cidade' => $validatedData['cidade'],
                    'uf' => $validatedData['uf'],
                    'id_nivel' => 2, // 2 = Profissional
                    'ativo' => 'S',
                    'genero' => $validatedData['genero'],
                ]);

                $user->profissional()->create([
                    'cpf' => $validatedData['cpf'],
                    'especialidade' => $validatedData['especialidade'],
                ]);
            });
            return response()->json(['success' => true, 'message' => 'Profissional criado!']);
        } catch (\Exception $e) {
            Log::error("Erro ao salvar profissional: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 3. Atualiza um profissional (via AJAX)
     * Responde à rota: /api/gestao_prof/update/{id}
     */
    public function updateApi(Request $request, $id)
    {
        $profissional = Profissional::findOrFail($id);
        $user = $profissional->user;

        // Limpa o CPF (remove '.', '-') antes de validar
        $request->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf)
        ]);

        // Validação para atualização
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'telefone' => 'required|string|max:20',
            'dt_nasc' => 'required|date',
            'cep' => 'required|string|max:9',
            'endereco' => 'required|string|max:100',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'uf' => 'required|string|size:2',
            'genero' => 'required|in:M,F,O',

            // ***** ESTA É A LINHA QUE CORRIGE O SEU ERRO *****
            // Ela diz ao Laravel para ignorar o 'idProfissional' do profissional atual
            'cpf' => ['required', 'string', 'digits:11', Rule::unique('profissional', 'cpf')->ignore($profissional->idProfissional, 'idProfissional')],

            'especialidade' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($user, $profissional, $request) {
                // 1. Atualizar o User
                $userData = $request->only('nome', 'email', 'telefone', 'dt_nasc', 'cep', 'endereco', 'bairro', 'cidade', 'uf');
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $userData['name'] = $userData['nome'];
                unset($userData['nome']);

                $user->update($userData);

                // 2. Atualizar o Profissional (agora com o CPF limpo)
                $profissional->update($request->only('cpf', 'especialidade'));
            });

            return response()->json(['success' => true, 'message' => 'Profissional atualizado!']);

        } catch (\Exception $e) {
            Log::error("Erro ao atualizar profissional: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 4. Deleta um profissional (via AJAX)
     * Responde à rota: /api/gestao_prof/destroy/{id}
     */
    public function destroyApi($id)
    {
        try {
            $profissional = Profissional::findOrFail($id);
            $profissional->user->delete(); // Deleta o usuário (o profissional irá junto com 'onDelete cascade')
            return response()->json(['success' => true, 'message' => 'Profissional deletado!']);
        } catch (\Exception $e) {
            Log::error("Erro ao deletar profissional: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $term = $request->input('q'); // O termo que o usuário digitou

        $query = Profissional::query();

        if ($term) {
            $query->whereHas('user', function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%");
            });
        }

        // Limita a 20 resultados para não pesar e traz o user junto
        $profissionais = $query->with('user')->limit(20)->get();

        // Formata para o padrão do Select2 (id e text)
        $results = $profissionais->map(function ($prof) {
            return [
                // Ajuste 'idProfissional' se sua chave primária tiver outro nome
                'id' => $prof->idProfissional ?? $prof->id,
                'text' => $prof->user->name . ' - ' . $prof->especialidade
            ];
        });

        return response()->json($results);
    }
}
