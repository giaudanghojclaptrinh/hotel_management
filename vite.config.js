import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/tailwind-config.js',

                //client
                //css
                'resources/css/login.css',
                'resources/css/register.css',
                'resources/css/password.css',
                'resources/css/client/home.css',
                'resources/css/client/rooms.css',
                'resources/css/client/profile.css',
                'resources/css/client/booking.css',
                'resources/css/client/about.css',
                'resources/css/client/contact.css',
                'resources/css/client/promo.css',
                'resources/css/client/notifications.css',

                //js
                'resources/js/login.js',
                'resources/js/register.js',
                'resources/js/password.js',
                'resources/js/client/home.js',
                'resources/js/client/rooms.js',
                'resources/js/client/profile.js',
                'resources/js/client/booking.js',
                'resources/js/client/about.js',
                'resources/js/client/contact.js',
                'resources/js/client/promo.js',
                'resources/js/client/notifications.js',

            ],
            refresh: true,
        }),
    ],
});