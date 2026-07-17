<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ★パターンC: SPA なので spa=true(自動 page_view を止める。遷移は app.jsx で手動送信)--}}
    @include('partials.ga4', ['spa' => true])

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @inertiaHead
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    @inertia
</body>
</html>
