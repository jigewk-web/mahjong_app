@extends('layouts.app')
@section('content')
@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif

<div class="center-wrapper">
    <div class="content-card">
        <h1>対局一覧</h1>
        <p class="create-text">
            <a href="{{ route('games.create') }}" class="create-button">＋ 対局を作成する</a>
        </p>

        {{--検索フォーム--}}
        <form method="GET" action="{{ route('games.index') }}" class="search-form">

            <div class="search-fields">
                <label>状態</label>
                <select name="status">
                    <option value="">すべて</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>対局中</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>終局済</option>
                </select>

                <label>開始日</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}">

                <label>終了日</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}">

                <label>プレイヤー</label>
                <select name="player_id">
                    <option value="">すべて</option>
                    @foreach ($players as $player)
                    <option value="{{ $player->id }}" {{ request('player_id') == $player->id ? 'selected' : '' }}>
                        {{ $player->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="search-buttons">
                <button type="submit" class="search-button">検索</button>
                <button type="button" class="search-button"
                    onclick="location.href='{{ route('games.index') }}'">
                    クリア
                </button>
            </div>

        </form>

        @foreach ($games as $game)
        <div class="game-card">
            <div class="game-card-header">
                <div class="game-card-title">
                    <h2>
                        <a href="{{ route('games.show', $game->id) }}">
                            {{ $game->played_at->format('Y/m/d H:i') }}
                        </a>
                    </h2>

                    <span class="status-label {{ $game->status == 0 ? 'status-active' : 'status-finished' }}">
                        {{ $game->status_label }}
                    </span>
                </div>

                @if ($game->status == 0)
                <a href="{{ route('games.result.edit', $game) }}" class="action-button result-button">
                    結果登録
                </a>
                @endif
            </div>

            <ul class="player-grid-list">
                @foreach ($game->players as $player)
                <li>
                    {{ [
                    'east' => '東',
                    'south' => '南',
                    'west' => '西',
                    'north' => '北'
                ][$player->pivot->seat] }}家　
                    {{ $player->name }}

                    @if ($game->status == 1)
                    - {{ $player->pivot->rank }}位
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
</div>
@endsection