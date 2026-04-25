<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function gamePlayers()
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_players')
            ->withPivot(['seat', 'rank', 'final_score', 'final_point'])
            ->withTimestamps();
    }
}
