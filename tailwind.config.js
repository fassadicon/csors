import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // UPDATE COLORS HERE
            colors: {
                'jt-primary': '#277F71',
                'jt-primary-dark': '#226C60',
                'jt-primary-light': '#D9F2EE',
                'jt-secondary': '#EB862A',
                'jt-light': '#FAFAFC',
                'jt-white': '#FFFFFF',
                'jt-grey': '#E8EBEA',
            }
        },
    },

    plugins: [
        forms,
        require("daisyui")
    ],

    darkMode: 'selector',
    daisyui: {
        themes: ['light'], // false: only light + dark | true: all themes | array: specific themes like this ["light", "dark", "cupcake"]
        base: true, // applies background color and foreground color for root element by default
        styled: true, // include daisyUI colors and design decisions for all components
        utils: true, // adds responsive and modifier utility classes
    },
};
