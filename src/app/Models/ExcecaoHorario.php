<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcecaoHorario extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'excecoes_horario';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idProfissional',
        'data',
        'hora_inicio',
        'hora_fim',
        'folga',
        'observacao',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     * Útil para garantir que 'data' seja um objeto Carbon e 'folga' seja booleano.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'date',
        'folga' => 'boolean',
    ];

    /**
     * Define o relacionamento: Uma exceção de horário pertence a um Profissional.
     */
    public function profissional()
    {
        // O Laravel vai ligar a coluna 'idProfissional' deste model
        // com a chave primária 'idProfissional' do model Profissional.
        return $this->belongsTo(Profissional::class, 'idProfissional', 'idProfissional');
    }
}