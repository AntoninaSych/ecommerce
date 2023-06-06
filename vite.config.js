import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    watchOptions: {
        ignored: '**/node_modules',
    },
    server: {
        host: '192.168.56.10',
        port: '5173',
        watch: {
            usePolling: true,
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
