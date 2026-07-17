<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| GA4 実装デモ 3パターン
|--------------------------------------------------------------------------
| A) Blade 主体(MPA)      : / と /about  → 物理ロードごとに page_view 自動送信
| B) 管理画面 分離         : /admin        → GA4 タグを入れない = 計測対象外
| C) Inertia + React SPA   : /app 配下     → 遷移を router.on('navigate') で手動送信
*/

// --- パターンA: Blade 主体(MPA) ---
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');

// --- パターンB: 管理画面(GA4なし) ---
Route::get('/admin', fn () => view('admin'))->name('admin');

// --- パターンC: Inertia + React SPA ---
Route::get('/app', fn () => Inertia::render('AppHome', [
    'message' => 'Inertia のトップページ',
]))->name('app.home');

Route::get('/app/page2', fn () => Inertia::render('AppPage2', [
    'message' => 'Inertia の2ページ目',
]))->name('app.page2');
