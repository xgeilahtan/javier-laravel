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
        // belongsTo(Classe, ChaveEstrangeiraNaTabelaAgendamentos, ChavePrimariaNaTabelaClientes)
        return $this->belongsTo(Cliente::class, 'id_cliente', 'idCliente');
    }

    // 2. Aponta para o Model PROFISSIONAL
    public function profissional()
    {
        // belongsTo(Classe, ChaveEstrangeiraNaTabelaAgendamentos, ChavePrimariaNaTabelaProfissional)
        return $this->belongsTo(Profissional::class, 'id_profissional', 'idProfissional');
    }

    /**
     * O serviço que foi agendado.
     */
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'id_servico', 'id_servico');
    }
}
