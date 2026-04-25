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
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            // 共有URL用
            $table->uuid('uuid')->nullable()->unique();

            // 対局名
            $table->string('name')->nullable();

            // 初期持ち点
            $table->integer('initial_score')->default(25000);

            // ステータス（0:対局中 / 1:終了）
            $table->tinyInteger('status')->default(0);

            // 対局日
            $table->dateTime('played_at')->nullable();

            // ルール設定
            $table->integer('oka')->default(0);
            $table->integer('uma_1')->default(20);
            $table->integer('uma_2')->default(10);
            $table->integer('uma_3')->default(-10);
            $table->integer('uma_4')->default(-20);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
