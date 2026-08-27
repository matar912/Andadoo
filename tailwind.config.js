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
                // Palette "escale nocturne" : nuit teal de l'Atlantique + sable chaud +
                // orange coucher de soleil (piste d'aeroport / signal d'accueil).
                night: {
                    50: '#EAF3F1', 100: '#C7E0DB', 300: '#5F988E',
                    500: '#0F3D3E', 700: '#0A2B2C', 900: '#061C1D',
                },
                sand: {
                    50: '#FBF7EF', 100: '#F3EAD3', 300: '#E4D2A4', 500: '#CBAE6B',
                },
                runway: {
                    400: '#F2A24A', 500: '#E8792E', 600: '#C25B1B', 700: '#973F0F',
                },
            },
            borderRadius: { xl2: '1.25rem' },
        },
    },
    plugins: [],
};
