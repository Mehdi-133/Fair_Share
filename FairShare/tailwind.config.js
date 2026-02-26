import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};

// module.exports = {
//   theme: {
//     extend: {
//       colors: {
//         // Our 2026 SaaS Palette
//         primary: {
//           50: '#f5f7ff',
//           100: '#ebf0fe',
//           600: '#4f46e5', // The Modern Deep Blue
//           700: '#4338ca',
//         },
//         slate: {
//           50: '#f8fafc', // Our off-white background
//           900: '#0f172a', // Our deep text color
//         }
//       },
//       borderRadius: {
//         'xl': '1rem',
//         '2xl': '1.5rem',
//         '3xl': '2rem', // Used for those extra-soft cards
//       },
//       fontFamily: {
//         sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui'],
//       },
//     },
//   },
// }
