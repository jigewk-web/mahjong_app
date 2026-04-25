<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 対局一覧
Route::get('/games', [GameController::class, 'index'])->name('games.index');

// 対局作成
Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
Route::post('/games', [GameController::class, 'store'])->name('games.store');

// 対局詳細
Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');

//対局結果登録
Route::get('/games/{game}/result', [GameController::class, 'editResult'])->name('games.result.edit');
Route::post('/games/{game}/result', [GameController::class, 'updateResult'])->name('games.result.update');

//プレイヤー登録
Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');
Route::post('/players', [PlayerController::class, 'store'])->name('players.store');

//編集・削除
Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
