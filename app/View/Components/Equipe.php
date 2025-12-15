<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Equipe extends Component
{
    /**
     * A URL da imagem do membro da equipe.
     */
    public string $image;

    /**
     * O nome do membro da equipe.
     */
    public string $name;

    /**
     * A especialidade ou descrição do membro.
     */
    public string $specialty;

    /**
     * O texto alternativo para a imagem.
     */
    public string $alt;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $image, string $name, string $specialty, string $alt = '')
    {
        $this->image = $image;
        $this->name = $name;
        $this->specialty = $specialty;
        
        // Se o alt não for fornecido, cria um padrão a partir do nome
        $this->alt = $alt ?: "Foto de {$name}";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.equipe');
    }
}
