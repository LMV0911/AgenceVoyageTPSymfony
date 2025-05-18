/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
    theme: {
        extend: {
            colors: {
                "admin-primary": "#1a365d",
                "admin-secondary": "#2d3748",
            },
        },
    },
    plugins: [],
};
