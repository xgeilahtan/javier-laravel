<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use File;

class Carrossel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
         $path = public_path('images/Carrossel');

        // 2. Pega todos os arquivos da pasta
        $files = File::files($path);

        // 3. Gera uma lista de URLs acessíveis
        $imagens = array_map(function ($file) {
            return asset('images/Carrossel/' . $file->getFilename());
        }, $files);

        // 4. Retorna a view do componente com os dados
        return view('components.carrossel', compact('imagens'));
    }
}
