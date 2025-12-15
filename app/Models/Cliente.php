<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'idCliente';
    public $timestamps = false;

    protected $fillable = ['user_id', 'observacoes'];

    /**
     * O usuário associado a este perfil de cliente.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}