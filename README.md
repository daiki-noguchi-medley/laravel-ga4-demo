# Laravel + Vite (React / Inertia) + GA4 実装デモ

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Vite](https://img.shields.io/badge/Vite-8-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![React](https://img.shields.io/badge/React-19-61DAFB?logo=react&logoColor=black)](https://react.dev)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2-9553E9?logo=inertia&logoColor=white)](https://inertiajs.com)
[![Docker](https://img.shields.io/badge/Docker-compose-2496ED?logo=docker&logoColor=white)](https://www.docker.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Laravel + Vite 上で **Google Analytics 4 (GA4)** の計測を **3パターン** で実装した検証用サンプルです。「Blade 主体 / 管理画面分離 / Inertia SPA」で実装がどう変わるかを、1つのアプリで並べて確認できます。

> GA4 の実装は突き詰めると「**gtag タグをどのレイアウトに置くか**」の一点。中核は [`resources/views/partials/ga4.blade.php`](resources/views/partials/ga4.blade.php) 1ファイルに集約しています。

## 実装パターン

| ルート | パターン | GA4 の挙動 |
|--------|----------|-----------|
| `/` , `/about` | **A: Blade 主体 (MPA)** | 物理ロードごとに `page_view` を自動送信 |
| `/admin` | **B: 管理画面 分離** | GA4 タグを入れない = 計測対象外 |
| `/app` , `/app/page2` | **C: Inertia + React SPA** | 遷移を `router.on('navigate')` で `page_view` を手動送信 |

A・B・C は同一プロジェクト内で共存できます。

## アーキテクチャ

`vite.config.js` の `input[]`(= 作れるエントリの一覧)がビルドされて `manifest.json` になり、各 Blade は `@vite()` で必要なエントリだけを選んで呼び出します。GA4 タグは「どのレイアウトに置くか」だけで計測対象が決まります。

```mermaid
flowchart LR
    IN["vite.config.js<br/>input[]"] -->|build| MAN["public/build<br/>manifest.json"]
    MAN --> V["Blade: @vite()"]

    V --> UL["layouts/user.blade.php<br/>@include('partials.ga4')"]
    V --> AL["layouts/admin.blade.php<br/>(GA4なし)"]
    V --> RB["app.blade.php<br/>@include('partials.ga4', spa=true)"]

    UL -->|自動 page_view| GA(["GA4"])
    RB -->|手動 page_view| GA
    AL -. 送信なし .-> GA
```

## 計測フロー(シーケンス)

### パターンA:Blade 主体(MPA)— ページ遷移が物理ロード

```mermaid
sequenceDiagram
    participant B as ブラウザ
    participant L as Laravel (Blade)
    participant G as GA4
    B->>L: GET /
    L-->>B: HTML(gtag タグ入り)
    B->>G: page_view(自動送信)
    Note over B,L: /about へ移動 = 物理ロード
    B->>L: GET /about
    L-->>B: HTML
    B->>G: page_view(自動送信)
```

### パターンC:Inertia + React SPA — 遷移は物理ロードしない

```mermaid
sequenceDiagram
    participant B as ブラウザ (React)
    participant L as Laravel (Inertia)
    participant G as GA4
    B->>L: 初回 GET /app
    L-->>B: HTML(send_page_view: false)
    B->>G: page_view /app(手動送信)
    Note over B,L: Page2 へ SPA 遷移(XHR / 物理ロードなし)
    B->>L: Inertia visit /app/page2
    L-->>B: JSON(page props)
    B->>G: page_view /app/page2(router.on navigate で手動送信)
```

> パターンB(管理画面)は GA4 タグ自体が無いため、どの操作でも GA4 への送信は発生しません。

## 動作検証 (GA4 DebugView)

`debug_mode` を付けて送っているため、**公開せずローカル (localhost) のまま** GA4 の DebugView でリアルタイムに確認できます。`page_view` や自作イベント (`cta_click` / `signup_click`)、拡張計測の `scroll` などが流れます。

![GA4 DebugView に計測イベントが流れている様子](docs/ga4-debugview.jpg)

## クイックスタート

```bash
# 1. 測定ID を設定(空のままだと GA4 タグは出力されません)
cp .env.example .env
#   .env の GA4_MEASUREMENT_ID=G-XXXXXXXXXX を記入し、APP_KEY を生成
#   （Docker を使わない場合は composer install && php artisan key:generate）

# 2. 起動
docker compose up --build

# 3. ブラウザで開く
open http://localhost:8000
```

`.env` は Docker にバインドマウントされているため、測定IDを編集してブラウザを再読込するだけで反映されます(再ビルド不要)。

### 検証手順

- **GA4 → 管理 → DebugView** を開いた状態で各ルートを操作
  - `/` `/app` … `page_view` が届く / `/admin` … 何も届かない
  - `/app` ↔ `/app/page2` … SPA でもリロードなしで `page_view` が増える
- **DevTools → Network** で `google-analytics.com/g/collect` を検索しても確認可能

## 仕組み

`vite.config.js` の `input[]` に全エントリを列挙し、各 Blade は `@vite()` で必要なものだけ呼びます (`input` = 作れるエントリの一覧 / `@vite()` = その画面で使う選択)。GA4 の初期化は partial に一元化し、include 時の引数で挙動を切り替えます。

```blade
@include('partials.ga4')               {{-- MPA: 自動 page_view --}}
@include('partials.ga4', ['spa' => true]) {{-- SPA: 自動送信OFF + 手動送信 --}}
```

## ファイル構成

GA4 関連ファイルだけを抜粋(★ = 中核3ファイル)。

```text
laravel-ga4-demo/
├─ vite.config.js                       ★ input[] に3エントリを列挙(同期の起点)
├─ config/services.php                    ga4.id を env から読む
├─ routes/web.php                         / /about /admin /app のルート
├─ bootstrap/app.php                      Inertia ミドルウェア登録
├─ app/Http/Middleware/
│   └─ HandleInertiaRequests.php          Inertia 共有props(rootView=app)
├─ resources/
│   ├─ css/app.css                        Tailwind
│   ├─ js/
│   │   ├─ blade.js                       パターンA/B: Alpine 起動
│   │   ├─ app.jsx                      ★ パターンC: Inertia + 手動 page_view
│   │   └─ Pages/
│   │       ├─ AppHome.jsx               SPA ページ1
│   │       └─ AppPage2.jsx              SPA ページ2
│   └─ views/
│       ├─ partials/ga4.blade.php       ★ GA4タグの一元管理(中核)
│       ├─ layouts/
│       │   ├─ user.blade.php            GA4あり(ユーザ面)
│       │   └─ admin.blade.php           GA4なし(管理画面)
│       ├─ home.blade.php                パターンA(/)
│       ├─ about.blade.php               パターンA(/about)
│       ├─ admin.blade.php               パターンB(/admin)
│       └─ app.blade.php                 パターンC: Inertiaルート(spa=true)
├─ Dockerfile                            2段ビルド(Vite → PHP)
├─ docker-compose.yml                    localhost:8000 / .env をバインドマウント
└─ .env                                  GA4_MEASUREMENT_ID
```

## 技術スタック

Laravel 13 / PHP 8.4 / Vite 8 / Tailwind CSS 4 / React 19 / Inertia.js 2 / Alpine.js 3 / Docker

## License

[MIT](LICENSE)
