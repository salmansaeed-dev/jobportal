// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {},
            fontFamily: {},
            screens: {
                '3xl': '1680px'
            },
        },
    },
    plugins: [],
}
