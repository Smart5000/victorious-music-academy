import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Merriweather Sans', ...defaultTheme.fontFamily.sans],
                display: ['Gloock', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    DEFAULT: '#513CC7',
                    soft: '#F8F6F2',
                    light: 'rgb(81 60 199 / 0.10)',
                    muted: 'rgb(81 60 199 / 0.16)',
                },
            },
        },
    },

    plugins: [forms],
};
