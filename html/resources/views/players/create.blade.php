@extends('layouts.app')

@section('content')
<div class="center-wrapper">
    <div class="form-card">
        <h1>プレイヤー登録</h1>

        <form method="POST" action="{{ route('players.store') }}">
            @csrf

            <div class="form-section player-form">
                <label>プレイヤー名</label>
                <input type="text" name="name" value="{{ old('name') }}">

                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="submit-button">登録</button>
        </form>
    </div>
</div>
@endsection