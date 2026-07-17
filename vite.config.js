import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // ★ここが「作れるエントリの一覧」。Blade の @vite() はこの中から選んで呼ぶ。
            input: [
                'resources/css/app.css',
                'resources/js/blade.js', // パターンA/B: Blade + Alpine 用
                'resources/js/app.jsx',  // パターンC: Inertia + React SPA 用
            ],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
