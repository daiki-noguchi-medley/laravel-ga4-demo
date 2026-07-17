@extends('layouts.admin')
@section('title', '管理画面')

@section('content')
    <h1 class="text-2xl font-bold">パターンB: 管理画面(GA4なし)</h1>
    <p class="mt-2 text-zinc-400">
        この画面は <code>layouts/admin.blade.php</code> を使っており、GA4 タグを一切埋め込んでいません。
        そのため <code>google-analytics.com/g/collect</code> へのリクエストは発生せず、DebugView にも出ません。
        「ユーザ面レイアウトにだけ GA4 を入れる」だけで、管理画面は設定ゼロで計測対象外になります。
    </p>
    <p class="mt-6 text-xs text-zinc-500">
        検証: DevTools の Network で collect リクエストが <b>飛ばない</b> ことを確認。
    </p>
@endsection
