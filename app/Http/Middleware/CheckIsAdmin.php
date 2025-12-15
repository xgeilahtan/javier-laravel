<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importe o Auth

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // =================================================================
        // ATENÇÃO: MUDE O NÚMERO '3' SE O SEU ID DE ADMIN FOR OUTRO!
        // =================================================================
        $idAdmin = 1; 

        if (Auth::check() && Auth::user()->id_nivel == $idAdmin) {
            // Se o usuário está logado E tem o id_nivel de Admin,
            // deixe a requisição continuar.
            return $next($request);
        }

        // --- Se o usuário NÃO for admin ---

        // Se for uma requisição da nossa API (AJAX), retorna um erro JSON
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Acesso não autorizado.'], 403);
        }

        // Se for uma tentativa de acessar a página pelo navegador,
        // redireciona para a home com uma mensagem de erro.
        return redirect()->route('home')->with('error', 'Você não tem permissão para acessar esta página.');
    }
}