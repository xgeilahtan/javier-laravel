<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    use HasFactory;
    protected $table = 'profissional';
    protected $primaryKey = 'idProfissional';
    public $timestamps = false; // Se sua tabela não tiver created_at/updated_at
    protected $fillable = ['user_id', 'cpf', 'especialidade'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'profissionais_servicos', 'user_id', 'id_servico');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_profissional', 'idProfissional');
    }

    public function excecoesHorario()
    {
        return $this->hasMany(ExcecaoHorario::class, 'idProfissional', 'idProfissional');
    }
}