<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();

            // 外部キー
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();

            // 席（東南西北）
            $table->enum('seat', ['east', 'south', 'west', 'north']);

            // 順位（1〜4のみ）
            $table->tinyInteger('rank')->nullable();

            // 最終持ち点
            $table->integer('final_score')->nullable();

            // 最終ポイント
            $table->decimal('final_point', 8, 1)->nullable();

            $table->timestamps();

            //席の重複なし
            $table->unique(['game_id', 'seat']);

            //プレイヤー重複なし
            $table->unique(['game_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
