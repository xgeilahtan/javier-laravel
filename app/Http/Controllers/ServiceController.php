<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\User;

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
}