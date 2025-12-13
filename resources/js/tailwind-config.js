import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    // QUAN TRỌNG: content giúp Tailwind biết cần quét file nào để sinh ra CSS
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php', // Quét toàn bộ file Blade
        './resources/js/**/*.js', // Quét toàn bộ file JS (nếu bạn gán class trong JS)
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Be Vietnam Pro"', 'sans-serif'],
                serif: ['"Playfair Display"', 'serif'],
            },
            colors: {
                brand: {
                    50: '#f4f7f6',
                    100: '#e0f2fe',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    800: '#1f2937',
                    900: '#111827', // Xanh đen (Dark Blue)
                    gold: '#c5a47e', // Vàng Gold (Luxury)
                    dark: '#1a1a1a',
                }
            }
        },
    },
    plugins: [],
};