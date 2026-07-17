<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ユーザ面') | GA4 Demo</title>

    {{-- ★パターンA/B: ユーザ面レイアウトにだけ GA4 を入れる(spa=false = 自動 page_view) --}}
    @include('partials.ga4')

    @vite(['resources/css/app.css', 'resources/js/blade.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <header class="bg-white border-b">
        <nav class="mx-auto max-w-3xl flex gap-4 p-4 text-sm">
            <a href="{{ route('home') }}" class="font-semibold hover:text-blue-600">Home</a>
            <a href="{{ route('about') }}" class="hover:text-blue-600">About</a>
            <a href="{{ route('admin') }}" class="hover:text-blue-600">管理画面(GA4なし)</a>
            <a href="{{ route('app.home') }}" class="hover:text-blue-600">Inertia SPA</a>
            <span class="ml-auto rounded bg-green-100 px-2 py-0.5 text-green-700">GA4: 計測ON</span>
        </nav>
    </header>

    <main class="mx-auto max-w-3xl p-6">
        @yield('content')
    </main>
</body>
</html>
