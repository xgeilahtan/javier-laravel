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

    public $imagens;

    /**
     * Create a new component instance.
     *
     * @param array $imagens
     */
    public function __construct($imagens = [])
    {
        $this->imagens = $imagens;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Se não vier imagens do controller, tenta carregar do local (fallback)
        if (empty($this->imagens)) {
            $path = public_path('images/Carrossel');
            if (File::exists($path)) {
                $files = File::files($path);
                $this->imagens = array_map(function ($file) {
                    return asset('images/Carrossel/' . $file->getFilename());
                }, $files);
            }
        }

        return view('components.carrossel', ['imagens' => $this->imagens]);
    }
}
