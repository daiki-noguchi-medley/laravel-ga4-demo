import { Link } from '@inertiajs/react';

export default function AppHome({ message }) {
    return (
        <div className="mx-auto max-w-3xl p-6">
            <div className="mb-4 flex gap-4 text-sm">
                <Link href="/app" className="font-semibold text-blue-600">App Home</Link>
                <Link href="/app/page2" className="text-blue-600 hover:underline">Page2</Link>
                <a href="/" className="ml-auto text-slate-500 hover:underline">← Blade側へ</a>
            </div>

            <h1 className="text-2xl font-bold">パターンC: Inertia + React SPA</h1>
            <p className="mt-2 text-slate-600">{message}</p>
            <p className="mt-2 text-slate-600">
                上の <b>Page2</b> リンクを押すと、ページはリロードされず(SPA遷移)URLだけ変わります。
                このとき <code>router.on('navigate')</code> が発火して <code>page_view</code> を手動送信します。
            </p>

            <button
                className="mt-6 rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                onClick={() => window.gtag && window.gtag('event', 'cta_click', { location: 'app_home' })}
            >
                cta_click を送信
            </button>

            <p className="mt-6 text-xs text-slate-400">
                検証: Page2 と行き来して、DebugView / Network に page_view が「遷移のたびに」増えれば成功。
            </p>
        </div>
    );
}
