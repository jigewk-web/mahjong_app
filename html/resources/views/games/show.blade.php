@extends('layouts.app')
@section('content')
@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif

<div class="form-card">
    <div class="page-header">
        <h1>対局詳細</h1>

        <div class="action-buttons">
            @if ($game->status == 0)
            <a href="{{ route('games.result.edit', $game) }}" class="action-button result-button">
                結果登録
            </a>
            @endif
            <a href="{{ route('games.edit', $game) }}" class="action-button">
                編集
            </a>

            <form method="POST" action="{{ route('games.destroy', $game) }}">
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="action-button delete-button"
                    onclick="return confirm('この対局を削除しますか？')">
                    削除
                </button>
            </form>
        </div>
    </div>

    <div class="form-section">
        <h2>対局情報</h2>

        <p>日時： {{ \Carbon\Carbon::parse($game->played_at)->format('Y/m/d H:i') }}</p>
        <p>返し点：{{ number_format($game->return_score) }}</p>
        <p>
            ウマ：
            {{ $game->uma_1 }} /
            {{ $game->uma_2 }} /
            {{ $game->uma_3 }} /
            {{ $game->uma_4 }}
        </p>
        <p>オカ： {{ $game->oka }}</p>
        <p>状態： {{ $game->status_label }}</p>
    </div>

    <div class="form-section">
        <h2>参加プレイヤー</h2>

        <table class="player-table">
            <thead>
                <tr>
                    <th>風</th>
                    <th>名前</th>
                    <th>順位</th>
                    <th>素点</th>
                    <th>pt</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($game->players as $player)
                <tr>
                    <td>{{ [
                        'east' => '東',
                        'south' => '南',
                        'west' => '西',
                        'north' => '北'
                        ][$player->pivot->seat] }}
                    </td>

                    <td>{{ $player->name }}</td>
                    <td>{{ $player->pivot->rank }}位</td>
                    <td>{{ number_format($player->pivot->final_score) }}</td>
                    <td>{{ number_format($player->pivot->final_point, 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection