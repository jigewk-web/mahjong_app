@extends('layouts.app')

@section('content')
<div class="form-card help-card">

    <h1>アプリの使い方</h1>

    <div class="help-section">
        <h2>1. 概要</h2>
        <p>
            このアプリは麻雀の対局結果を記録・管理するためのWebアプリです。<br>
            対局の作成から結果入力、順位・ポイントの計算まで行えます。
        </p>
    </div>

    <div class="help-section">
        <h2>2. プレイヤー登録</h2>
        <p>
            「プレイヤー登録」画面から名前を入力して登録します。<br>
            同じ名前は登録できません。
        </p>
    </div>

    <div class="help-section">
        <h2>3. 対局作成</h2>
        <p>
            対局日・ルール（返し点・ウマ・オカ）を設定し、4人のプレイヤーを選択して作成します。
        </p>
    </div>

    <div class="help-section">
        <h2>4. 対局結果入力</h2>
        <p>
            各プレイヤーの順位と素点を入力します。<br>
            順位は1〜4で重複できません。
        </p>
    </div>

    <div class="help-section">
        <h2>5. 検索機能</h2>
        <p>
            対局状態・日付・プレイヤーで絞り込みが可能です。
        </p>
    </div>

    <div class="help-section">
        <h2>6. 注意事項</h2>
        <ul>
            <li>同じプレイヤーは1対局に重複できません</li>
            <li>順位は1〜4で重複不可です</li>
            <li>終局後は自動でポイントが計算されます</li>
        </ul>
    </div>

</div>
@endsection