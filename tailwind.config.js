/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "gampong-primary": "#2D5016",
                "gampong-secondary": "#8FBC8F",
                "gampong-accent": "#FFD700",
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
