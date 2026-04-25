<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;

class GameController extends Controller
{
    // 対局一覧
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:0,1',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'player_id' => 'nullable|exists:players,id',
        ]);

        $players = Player::all();
        $query = Game::with('players');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('played_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('played_at', '<=', $request->to_date);
        }

        if ($request->filled('player_id')) {
            $query->whereHas('gamePlayers', function ($q) use ($request) {
                $q->where('player_id', $request->player_id);
            });
        }

        $games = $query->get();
        return view('games.index', compact('games', 'players'));
    }

    //対局詳細
    public function show(Game $game)
    {
        $game->load('players');
        return view('games.show', compact('game'));
    }

    //対局作成
    public function create()
    {
        $players = Player::orderBy('name')->get();
        return view('games.create', compact('players'));
    }

    //対局保存処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'played_at' => ['required', 'date'],
            'return_score' => ['required', 'integer'],
            'oka' => ['required', 'integer'],
            'uma_1' => ['required', 'integer'],
            'uma_2' => ['required', 'integer'],
            'uma_3' => ['required', 'integer'],
            'uma_4' => ['required', 'integer'],
            'players' => ['required', 'array'],
            'players.*' => ['required', 'distinct', 'exists:players,id'],
        ], [
            'played_at.required' => '対局日を入力してください。',
            'return_score.required' => '返し点を入力してください。',
            'oka.required' => 'オカを入力してください。',
            'uma_1.required' => 'ウマ1を入力してください。',
            'uma_2.required' => 'ウマ2を入力してください。',
            'uma_3.required' => 'ウマ3を入力してください。',
            'uma_4.required' => 'ウマ4を入力してください。',
            'players.*.required' => 'プレイヤーを選択してください。',
            'players.*.distinct' => '同じプレイヤーは選択できません。',
            'players.*.exists' => '存在しないプレイヤーです。',
        ]);

        $game = Game::create([
            'played_at' => $validated['played_at'],
            'status' => 0,
            'return_score' => $validated['return_score'],
            'oka' => $validated['oka'],
            'uma_1' => $validated['uma_1'],
            'uma_2' => $validated['uma_2'],
            'uma_3' => $validated['uma_3'],
            'uma_4' => $validated['uma_4'],
        ]);

        foreach ($validated['players'] as $seat => $playerId) {
            GamePlayer::create([
                'game_id' => $game->id,
                'player_id' => $playerId,
                'seat' => $seat,
            ]);
        }

        return redirect()->route('games.show', $game)
            ->with('success', '対局を作成しました');
    }

    //対局結果登録
    public function editResult(Game $game)
    {
        $game->load('gamePlayers.player');
        return view('games.result', compact('game'));
    }

    //対局結果保存処理
    public function updateResult(Request $request, Game $game)
    {
        $validated = $request->validate([
            'results' => ['required', 'array'],
            'results.*.rank' => ['required', 'integer', 'distinct', 'between:1,4'],
            'results.*.final_score' => ['required', 'integer'],
        ], [
            'results.*.rank.required' => '順位を入力してください。',
            'results.*.rank.integer' => '順位は数値で入力してください。',
            'results.*.rank.distinct' => '同じ順位は入力できません。',
            'results.*.rank.between' => '順位は1〜4で入力してください。',
            'results.*.final_score.required' => '素点を入力してください。',
            'results.*.final_score.integer' => '素点は数値で入力してください。',
        ]);

        foreach ($validated['results'] as $gamePlayerId => $result) {
            $gamePlayer = $game->gamePlayers()->find($gamePlayerId);

            if ($gamePlayer) {
                $gamePlayer->update([
                    'rank' => $result['rank'],
                    'final_score' => $result['final_score'],
                ]);
            }
        }

        $game->calculateFinalPoints();
        $game->update([
            'status' => 1
        ]);

        return redirect()->route('games.show', $game)
            ->with('success', '対局結果を登録しました');
    }

    //編集
    public function edit(Game $game)
    {
        $game->load('gamePlayers');
        $players = Player::all();
        return view('games.edit', compact('game', 'players'));
    }

    //更新
    public function update(Request $request, Game $game)
    {
        // バリデーション
        $validated = $request->validate([
            'played_at' => 'required|date',
            'return_score' => 'required|integer',
            'oka' => 'required|integer',
            'uma_1' => 'required|integer',
            'uma_2' => 'required|integer',
            'uma_3' => 'required|integer',
            'uma_4' => 'required|integer',
            'players' => 'required|array',
            'players.*' => ['required', 'distinct', 'exists:players,id'],
            'final_scores.*' => $game->status == 1 ? 'required|integer' : 'nullable|integer',
        ], [
            'played_at.required' => '対局日を入力してください。',
            'return_score.required' => '返し点を入力してください。',
            'oka.required' => 'オカを入力してください。',
            'uma_1.required' => 'ウマ1を入力してください。',
            'uma_2.required' => 'ウマ2を入力してください。',
            'uma_3.required' => 'ウマ3を入力してください。',
            'uma_4.required' => 'ウマ4を入力してください。',
            'players.*.required' => 'プレイヤーを選択してください。',
            'players.*.distinct' => '同じプレイヤーは選択できません。',
            'players.*.exists' => '存在しないプレイヤーです。',
            'final_scores.*.required' => '素点を入力してください。',
            'final_scores.*.integer' => '素点は数値で入力してください。',
        ]);

        // games テーブル更新
        $game->update([
            'played_at' => $validated['played_at'],
            'return_score' => $validated['return_score'],
            'oka' => $validated['oka'],
            'uma_1' => $validated['uma_1'],
            'uma_2' => $validated['uma_2'],
            'uma_3' => $validated['uma_3'],
            'uma_4' => $validated['uma_4'],
        ]);

        // game_players 更新
        foreach ($validated['players'] as $seat => $playerId) {
            $gamePlayer = $game->gamePlayers()->where('seat', $seat)->first();

            if ($gamePlayer) {
                // プレイヤー更新
                $gamePlayer->player_id = $playerId;

                // 終局済みなら素点更新
                if ($game->status == 1 && isset($validated['final_scores'][$seat])) {
                    $gamePlayer->final_score = $validated['final_scores'][$seat];
                }

                $gamePlayer->save();
            }
        }

        // 終局済みなら順位・ポイント再計算
        if ($game->status == 1) {
            // 順位再計算
            $rankedPlayers = $game->gamePlayers()
                ->orderByDesc('final_score')
                ->get();

            foreach ($rankedPlayers as $index => $gamePlayer) {
                $gamePlayer->rank = $index + 1;
                $gamePlayer->save();
            }

            // ポイント再計算
            $game->calculateFinalPoints();
        }

        return redirect()->route('games.show', $game)
            ->with('success', '対局情報を更新しました');
    }

    //削除
    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')
            ->with('success', '対局を削除しました');
    }
}
