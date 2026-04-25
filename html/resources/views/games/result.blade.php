@extends('layouts.app')
@section('content')
<div class="center-wrapper">
    <div class="form-card">
        <h1>対局結果登録</h1>

        @error('results.*.rank')
        <div class="error-message">{{ $message }}</div>
        @enderror
        @error('results.*.final_score')
        <div class="error-message">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('games.result.update', $game) }}">
            @csrf

            <div class="result-grid">
                @foreach ($game->gamePlayers as $gp)
                <div class="result-card">
                    <h3>
                        {{ [
                        'east' => '東',
                        'south' => '南',
                        'west' => '西',
                        'north' => '北'
                    ][$gp->seat] }}家
                        {{ $gp->player->name }}
                    </h3>
                    <div class="result-fields">
                        <div class="field-group">
                            <label>順位</label>
                            <select name="results[{{ $gp->id }}][rank]">
                                <option value="1" {{ old("results.$gp->id.rank", $gp->rank) == 1 ? 'selected' : '' }}>1位</option>
                                <option value="2" {{ old("results.$gp->id.rank", $gp->rank) == 2 ? 'selected' : '' }}>2位</option>
                                <option value="3" {{ old("results.$gp->id.rank", $gp->rank) == 3 ? 'selected' : '' }}>3位</option>
                                <option value="4" {{ old("results.$gp->id.rank", $gp->rank) == 4 ? 'selected' : '' }}>4位</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label>点数</label>
                            <input
                                type="number"
                                name="results[{{ $gp->id }}][final_score]"
                                value="{{ old("results.$gp->id.final_score", $gp->final_score) }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="submit-button">結果登録</button>
        </form>
    </div>
</div>
@endsection