<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '管理画面') | GA4 Demo</title>

    {{-- ★パターンB: 管理画面レイアウトには GA4 を入れない → 自動的に計測対象外 --}}

    @vite(['resources/css/app.css', 'resources/js/blade.js'])
</head>
<body class="min-h-screen bg-zinc-900 text-zinc-100">
    <header class="bg-zinc-800 border-b border-zinc-700">
        <nav class="mx-auto max-w-3xl flex gap-4 p-4 text-sm">
            <span class="font-semibold">管理画面</span>
            <a href="{{ route('home') }}" class="hover:text-blue-400">← ユーザ面へ戻る</a>
            <span class="ml-auto rounded bg-red-900 px-2 py-0.5 text-red-200">GA4: 計測OFF</span>
        </nav>
    </header>

    <main class="mx-auto max-w-3xl p-6">
        @yield('content')
    </main>
</body>
</html>
