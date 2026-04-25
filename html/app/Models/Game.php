<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'played_at',
        'status',
        'return_score',
        'oka',
        'uma_1',
        'uma_2',
        'uma_3',
        'uma_4',
    ];

    public function gamePlayers()
    {
        return $this->hasMany(GamePlayer::class);
    }

    // Playerを直接取りたい場合（便利）
    public function players()
    {
        return $this->belongsToMany(Player::class, 'game_players')
            ->withPivot(['seat', 'rank', 'final_score', 'final_point'])
            ->withTimestamps();
    }

    protected $casts = [
        'played_at' => 'datetime:1',
    ];

    //素点・オカ・ウマに基づいてポイント計算（挙動の確認まだ）
    public function calculateFinalPoints()
    {
        foreach ($this->gamePlayers as $gamePlayer) {
            $basePoint = ($gamePlayer->final_score - $this->return_score) / 1000;

            $uma = match ($gamePlayer->rank) {
                1 => $this->uma_1,
                2 => $this->uma_2,
                3 => $this->uma_3,
                4 => $this->uma_4,
                default => 0,
            };

            $oka = $gamePlayer->rank === 1 ? $this->oka : 0;

            $gamePlayer->final_point = $basePoint + $uma + $oka;
            $gamePlayer->save();
        }
    }

    //ステータス表示用
    public function getStatusLabelAttribute()
    {
        return $this->status == 0 ? '対局中' : '終局済';
    }
}
