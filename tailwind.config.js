export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                sage: {
                    50: '#f4f7f4',
                    100: '#e3ebe3',
                    200: '#c5d9c5',
                    300: '#9cbfa0',
                    400: '#75a079',
                    500: '#558359',
                    600: '#426846',
                    700: '#365339',
                    800: '#2d4230',
                    900: '#263728',
                }
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            }
        },
    },
    plugins: [],
}