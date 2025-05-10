import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'bg-green-100', 'bg-green-500', 'bg-green-400', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300',
        'bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300', 'text-green-100','text-green-50'
        // Các class cần thiết khác
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
