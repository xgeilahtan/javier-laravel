<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Armazena um novo cliente no banco de dados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefone' => 'required|string|max:20',
            'dt_nasc' => 'required|date',
            'genero' => 'required|in:M,F,O',
            'cep' => 'required|string|max:9',
            'endereco' => 'required|string|max:100',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'uf' => 'required|string|size:2',
            'observacoes' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'email.unique' => 'Este email ja foi cadastrado.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As senhas devem ser iguais.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'dt_nasc.required' => 'O campo data de nascimento é obrigatório.',
            'genero.required' => 'O campo sexo é obrigatório.',
            'cep.required' => 'O campo cep é obrigatório.',
            'endereco.required' => 'O campo endereço é obrigatório.',
            'bairro.required' => 'O campo bairro é obrigatório.',
            'cidade.required' => 'O campo cidade é obrigatório.',
            'uf.required' => 'O campo uf é obrigatório.',
            'foto.image' => 'O campo foto deve ser uma imagem.',
            'foto.mimes' => 'O campo foto deve ser uma imagem JPEG, PNG, JPG ou GIF.',
            'foto.max' => 'O campo foto deve ter no máximo 2MB.',
        ]);

        try {
            DB::transaction(function () use ($request, $validatedData) {
                // CÓDIGO NOVO (usando o caminho absoluto especificado)
                $pathFoto = null;
                if ($request->hasFile('foto')) {
                    $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
                    $destinationPath = base_path('src/public/images/clientes');
                    $request->file('foto')->move($destinationPath, $filename);
                    $pathFoto = 'images/clientes/' . $filename;
                }

                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'telefone' => $validatedData['telefone'],
                    'dt_nasc' => $validatedData['dt_nasc'],
                    'genero' => $validatedData['genero'],
                    'cep' => $validatedData['cep'],
                    'endereco' => $validatedData['endereco'],
                    'bairro' => $validatedData['bairro'],
                    'cidade' => $validatedData['cidade'],
                    'uf' => $validatedData['uf'],
                    'foto' => $pathFoto,
                    'id_nivel' => 3,
                    'ativo' => 'S',
                ]);

                Cliente::create([
                    'user_id' => $user->id,
                    'observacoes' => $validatedData['observacoes'] ?? null,
                ]);

                // Faz o login automático do usuário recém-criado
                Auth::login($user);
            });

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível concluir o cadastro. Por favor, tente novamente.')
                ->withInput();
        }

        // ALTERAÇÃO APLICADA AQUI
        return redirect()->route('home')->with('success', 'É um prazer receber você! Marque um horário conosco e realce sua beleza.');
    }
}