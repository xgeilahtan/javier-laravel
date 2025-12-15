<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefone',
        'id_nivel',
        'cep',
        'endereco',
        'bairro',
        'cidade',
        'uf',
        'ativo',
        'dt_nasc',
        'genero',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELACIONAMENTOS ---

    /**
     * O nível de acesso que este usuário possui (cliente, profissional, admin).
     */
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }

    /**
     * Os dados específicos de cliente, se o usuário for um.
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }

    /**
     * Os dados específicos de profissional, se o usuário for um.
     */
    public function profissional()
    {
        return $this->hasOne(Profissional::class, 'user_id');
    }

    /**
     * Os dados específicos de administrador, se o usuário for um.
     */
    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'user_id');
    }

    /**
     * Os serviços que este usuário (como profissional) pode realizar.
     */
    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'profissionais_servicos', 'user_id', 'id_servico');
    }

    /**
     * Os agendamentos que este usuário fez como cliente.
     */
    public function agendamentosComoCliente()
    {
        return $this->hasMany(Agendamento::class, 'id_cliente');
    }

    /**
     * Os agendamentos que este usuário tem como profissional.
     */
    public function agendamentosComoProfissional()
    {
        return $this->hasMany(Agendamento::class, 'id_profissional');
    }
}