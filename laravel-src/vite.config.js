import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),
    ],
    // 開発サーバー設定
    server: {
        // docker コンテナで起動された 5173へのアクセスとか
        host: true,
    }
});
