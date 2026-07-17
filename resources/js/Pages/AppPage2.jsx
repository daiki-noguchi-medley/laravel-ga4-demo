import { Link } from '@inertiajs/react';

export default function AppPage2({ message }) {
    return (
        <div className="mx-auto max-w-3xl p-6">
            <div className="mb-4 flex gap-4 text-sm">
                <Link href="/app" className="text-blue-600 hover:underline">App Home</Link>
                <Link href="/app/page2" className="font-semibold text-blue-600">Page2</Link>
                <a href="/" className="ml-auto text-slate-500 hover:underline">← Blade側へ</a>
            </div>

            <h1 className="text-2xl font-bold">Page2(SPA内の2ページ目)</h1>
            <p className="mt-2 text-slate-600">{message}</p>
            <p className="mt-2 text-slate-600">
                ここへ来た遷移でも手動 <code>page_view</code>(page_path=/app/page2)が送られています。
            </p>
        </div>
    );
}
