@extends('layouts.user')
@section('title', 'About')

@section('content')
    <h1 class="text-2xl font-bold">About(Blade MPA の2ページ目)</h1>
    <p class="mt-2 text-slate-600">
        Home からここへ来た時点で、物理ロードにより <code>page_view</code> がもう1件送られています。
        MPA では遷移計測のコードを書かなくても各ページが自動で計測されるのがポイントです。
    </p>
    <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 hover:underline">← Home に戻る</a>
@endsection
