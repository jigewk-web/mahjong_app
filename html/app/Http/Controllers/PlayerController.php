<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;


class PlayerController extends Controller
{
    public function create()
    {
        return view('players.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'unique:players,name', 'regex:/.*\S.*/'],
            ],
            [
                'name.required' => 'プレイヤー名を入力してください。',
                'name.unique' => 'このプレイヤー名はすでに登録されています。',
                'name.max' => 'プレイヤー名は255文字以内で入力してください。',
                'name.regex' => 'プレイヤー名を入力してください。',
            ]
        );

        Player::create($validated);

        return redirect()->route('games.index')
            ->with('success', 'プレイヤーを登録しました。');
    }
}
