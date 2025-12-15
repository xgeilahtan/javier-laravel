<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'administrador';
    protected $primaryKey = 'idAdministrador';
    public $timestamps = false;

    protected $fillable = ['user_id'];

    /**
     * O usuário associado a este perfil de administrador.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}