import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/not-found.jsx',
                'resources/js/error-page.jsx',
                'resources/js/stroke-title-entry.ts',
                'resources/js/aurora-login-entry.ts',
                'resources/js/dashboard-anim.ts',
                'resources/js/dashboard-filters-entry.tsx',
            ],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
});
