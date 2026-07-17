@extends('layouts.user')
@section('title', 'Home')

@section('content')
    <h1 class="text-2xl font-bold">パターンA: Blade 主体(MPA)</h1>
    <p class="mt-2 text-slate-600">
        このページを開いた瞬間に <code>page_view</code> が自動送信されます(gtag が物理ロードで送るため)。
        上部メニューの <b>About</b> に移動すると、また物理ロードが起きてもう1件 <code>page_view</code> が飛びます。
    </p>

    <div class="mt-6 rounded border bg-white p-4" x-data="{ sent: 0 }">
        <p class="text-sm text-slate-500">Alpine から任意イベントを送るデモ:</p>
        <button
            class="mt-2 rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            @click="window.gtag && window.gtag('event', 'signup_click', { method: 'demo' }); sent++"
        >
            signup_click を送信
        </button>
        <span class="ml-3 text-sm text-slate-500">送信回数: <span x-text="sent"></span></span>
    </div>

    <p class="mt-6 text-xs text-slate-400">
        検証: DevTools の Network で <code>google-analytics.com/g/collect</code> を確認 / GA4 DebugView に出れば成功。
    </p>
@endsection
