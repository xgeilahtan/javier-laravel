<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveis';
    protected $primaryKey = 'id_nivel';
    public $timestamps = false;

    protected $fillable = ['descricao'];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_nivel', 'id_nivel');
    }
}