import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/tailwind-config.js',
                'resources/js/client.css',
                'resources/js/client.js',
            ],
            refresh: true,
        }),
    ],
});