<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    protected $table = 'agendamentos';
    protected $primaryKey = 'id_agendamento';

    protected $fillable = [
        'id_cliente',
        'id_profissional',
        'id_servico',
        'data_hora_inicio',
        'data_hora_fim',
        'observacoes',
        'status',
    ];

    /**
     * O cliente (usuário) do agendamento.
     */
    public function cliente()
    {
        return $this->belongsTo(User::class, 'id_cliente');
    }

    /**
     * O profissional (usuário) do agendamento.
     */
    public function profissional()
    {
        return $this->belongsTo(User::class, 'id_profissional');
    }

    /**
     * O serviço que foi agendado.
     */
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'id_servico', 'id_servico');
    }
}