// パターンC: Inertia + React SPA のエントリ。
// SPA はページ遷移で物理ロードしないため、gtag の自動 page_view は初回しか飛ばない。
// → router の 'navigate' イベントを拾って、遷移ごとに page_view を手動送信する。
import '../css/app.css';
import { createInertiaApp, router } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';

// 遷移ごとに GA4 へ page_view を手動送信
router.on('navigate', (event) => {
    if (typeof window.gtag !== 'function') return; // 測定ID未設定なら何もしない
    const url = event.detail.page.url;
    window.gtag('event', 'page_view', {
        page_path: url,
        page_location: window.location.origin + url,
        page_title: document.title,
    });
});

createInertiaApp({
    resolve: (name) => {
        // ページは動的 import で自動コード分割(input には書かない)
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});
