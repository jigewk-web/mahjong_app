@extends('layouts.app')

@section('content')
<div class="form-card">
    <h1>対局編集</h1>

    <form method="POST" action="{{ route('games.update', $game) }}">
        @csrf
        @method('PUT')

        {{-- 対局情報 --}}
        <div class="form-section">
            <h2>対局情報</h2>
            @error('played_at')
            <p class="error-message">{{ $message }}</p>
            @enderror
            <div class="field-group">
                <label>対局日時</label>
                <input
                    type="datetime-local"
                    name="played_at"
                    class="datetime-input"
                    value="{{ old('played_at', $game->played_at->format('Y-m-d\TH:i')) }}">
            </div>
        </div>

        {{-- プレイヤー --}}
        <div class="form-section">
            <h2>参加プレイヤー</h2>
            @error('players.*')
            <div class="error-message">{{ $message }}</div>
            @enderror

            @php
            $seats = [
            'east' => '東',
            'south' => '南',
            'west' => '西',
            'north' => '北',
            ];
            @endphp

            <div class="player-grid">
                @foreach ($seats as $seatKey => $seatLabel)
                @php
                $selectedPlayerId = optional(
                $game->gamePlayers->firstWhere('seat', $seatKey)
                )->player_id;
                @endphp

                <div class="field-group">
                    <label>{{ $seatLabel }}家</label>

                    <select name="players[{{ $seatKey }}]">
                        @foreach ($players as $player)
                        <option
                            value="{{ $player->id }}"
                            {{ old("players.$seatKey", $selectedPlayerId) == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>

        {{--対局結果--}}
        @if ($game->status == 1)
        <div class="form-section">
            <h2>対局結果</h2>
            @error('final_scores.*')
            <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="player-grid">
                @foreach ($seats as $seatKey => $seatLabel)
                @php
                $gamePlayer = $game->gamePlayers->firstWhere('seat', $seatKey);
                @endphp

                <div class="field-group">
                    <label>{{ $seatLabel }}家 素点</label>
                    <input
                        type="number"
                        name="final_scores[{{ $seatKey }}]"
                        value="{{ old("final_scores.$seatKey", $gamePlayer->final_score) }}">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ルール設定 --}}
        <div class="form-section">
            <h2>ルール設定</h2>
            @error('return_score')
            <div class="error-message">{{ $message }}</div>
            @enderror
            @error('oka')
            <div class="error-message">{{ $message }}</div>
            @enderror
            @if(
            $errors->has('uma_1') ||
            $errors->has('uma_2') ||
            $errors->has('uma_3') ||
            $errors->has('uma_4')
            )
            <p class="error-message">ウマを入力してください。</p>
            @endif

            <div class="rule-grid">
                <div class="field-group">
                    <label>返し点</label>
                    <input type="number" name="return_score" value="{{ old('return_score', $game->return_score) }}">
                </div>

                <div class="field-group">
                    <label>オカ</label>
                    <input type="number" name="oka" value="{{ old('oka', $game->oka) }}">
                </div>

                <div class="field-group">
                    <label>ウマ1位</label>
                    <input type="number" name="uma_1" value="{{ old('uma_1', $game->uma_1) }}">
                </div>

                <div class="field-group">
                    <label>ウマ2位</label>
                    <input type="number" name="uma_2" value="{{ old('uma_2', $game->uma_2) }}">
                </div>

                <div class="field-group">
                    <label>ウマ3位</label>
                    <input type="number" name="uma_3" value="{{ old('uma_3', $game->uma_3) }}">
                </div>

                <div class="field-group">
                    <label>ウマ4位</label>
                    <input type="number" name="uma_4" value="{{ old('uma_4', $game->uma_4) }}">
                </div>
            </div>
        </div>

        <button type="submit" class="submit-button">更新する</button>
    </form>
</div>
@endsection