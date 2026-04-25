<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>麻雀記録</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header>
        <nav>
            <a href="{{ route('games.index') }}">対局一覧</a>
            <a href="{{ route('games.create') }}">対局作成</a>
            <a href="{{ route('players.create') }}">プレイヤー登録</a>
        </nav>

    </header>


    <main>
        @yield('content')
    </main>
</body>

</html>