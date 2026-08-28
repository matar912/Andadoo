import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                sans: ['"Inter"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palette extraite du logo Andadoo : vert forêt profond + or,
                // sur fond creme chaud. "forest" = texte/fond sombre,
                // "paper" = fond clair, "gold" = accent.
                forest: {
                    50: '#EAF2EF', 100: '#C7DED6', 300: '#5C8F80',
                    500: '#0C3A30', 700: '#082D25', 900: '#012D27',
                },
                paper: {
                    50: '#FDFBF6', 100: '#F6F1E6', 300: '#E8DFC8', 500: '#CBBB8E',
                },
                gold: {
                    400: '#EEB548', 500: '#D89B2E', 600: '#C97D18', 700: '#9E6512',
                },
            },
            borderRadius: { xl2: '1.25rem' },
            boxShadow: {
                card: '0 1px 2px rgba(1, 45, 39, 0.05), 0 8px 24px -8px rgba(1, 45, 39, 0.12)',
            },
        },
    },
    plugins: [],
};
