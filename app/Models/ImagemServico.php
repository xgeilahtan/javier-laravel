<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemServico extends Model
{
    use HasFactory;

    protected $table = 'imagem_servicos';
    public $timestamps = false;
    protected $fillable = ['image_path', 'id_servico'];

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'id_servico', 'id_servico');
    }
}