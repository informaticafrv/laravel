<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videogame extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'game_id', 'plataforma', 'puntuacion_personal', 'estado',
        'nota_grafica', 'nota_historia', 'nota_jugabilidad', 'nota_duracion',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    //
}
