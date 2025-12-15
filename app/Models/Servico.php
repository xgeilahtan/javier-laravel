<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;
    protected $table = 'servicos';
    protected $primaryKey = 'id_servico';
    public $timestamps = false; // Se não tiver created_at/updated_at
    protected $fillable = ['nome', 'descricao', 'duracao_minutos', 'preco', 'categoria'];

    /**
     * Define o relacionamento com os usuários (que são profissionais)
     * através da tabela pivot 'profissionais_servicos'.
     */
    public function profissionais()
    {
        // A tabela 'profissionais_servicos' liga 'id_servico' com 'user_id'
        return $this->belongsToMany(User::class, 'profissionais_servicos', 'id_servico', 'user_id');
    }

    public function imagens()
    {
        return $this->hasMany(ImagemServico::class, 'id_servico', 'id_servico');
    }
}