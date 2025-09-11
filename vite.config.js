import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    // REMOVE this block completely:
    // define: {
    //     'import.meta.env.VITE_APP_URL': JSON.stringify(process.env.VITE_APP_URL || 'http://127.0.0.1:8000/api'),
    // },
});
