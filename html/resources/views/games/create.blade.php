@extends('layouts.app')

@section('content')
<div class="form-card">
    <h1>対局登録</h1>

    <form method="POST" action="{{ route('games.store') }}">
        @csrf

        <div class="form-section">
            <h2>対局情報</h2>
            @error('played_at')
            <p class="error-message">{{ $message }}</p>
            @enderror

            <label>対局日時</label>
            <input type="datetime-local" name="played_at" class="datetime-input"
                value="{{ now()->format('Y-m-d\TH:i') }}">
        </div>

        <div class="form-section">
            <h2>参加プレイヤー</h2>
            @php
            $seats = [
            'east' => '東',
            'south' => '南',
            'west' => '西',
            'north' => '北',
            ];
            @endphp

            @error('players.*')
            <div class="error-message">{{ $message }}</div>
            @enderror

            <div class="player-grid">
                @foreach ($seats as $seatKey => $seatLabel)
                <div class="field-group">
                    <label>{{ $seatLabel }}家</label>
                    <select name="players[{{ $seatKey }}]">
                        @foreach ($players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>

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
                    <input type="number" name="return_score" value="30000">
                </div>
                <div class="field-group">
                    <label>オカ</label>
                    <input type="number" name="oka" value="20">
                </div>
                <div class="field-group">
                    <label>ウマ1位</label>
                    <input type="number" name="uma_1" value="30">
                </div>
                <div class="field-group">
                    <label>ウマ2位</label>
                    <input type="number" name="uma_2" value="10">
                </div>
                <div class="field-group">
                    <label>ウマ3位</label>
                    <input type="number" name="uma_3" value="-10">
                </div>
                <div class="field-group">
                    <label>ウマ4位</label>
                    <input type="number" name="uma_4" value="-30">
                </div>
            </div>
        </div>

        <button type="submit" class="submit-button">登録</button>
    </form>
</div>
@endsection