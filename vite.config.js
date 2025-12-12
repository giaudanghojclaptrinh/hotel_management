import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/tailwind-config.js',
                'resources/css/login.css',
                'resources/js/login.js',
                'resources/css/register.css',
                'resources/js/register.js',
                'resources/css/password.css',
                'resources/js/password.js',
                'resources/css/client/home.css',
                'resources/js/client/home.js',
                'resources/css/client/rooms.css',
                'resources/js/client/rooms.js',
                'resources/css/client/profile.css',
                'resources/js/client/profile.js',
                'resources/css/client/booking.css',
                'resources/js/client/booking.js',
                'resources/js/client/about.js',
                'resources/css/client/about.css',
            ],
            refresh: true,
        }),
    ],
});