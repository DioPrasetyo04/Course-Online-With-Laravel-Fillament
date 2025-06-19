/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/**/*.{html,js}"],
    theme: {
        extend: {
            colors: {
                "obito-black": "#0A0723",
                "obito-green": "#2F6A62",
                "obito-grey": "#EAECEE",
                "obito-red": "#EF372B",
                "obito-text-grey": "#87898C",
                "obito-light-green": "#E0EAE8",
                "obito-light-red": "#FFE3E1",
                "obito-text-secondary": "#6A6F7C",
            },
            keyframes: {
                slide: {
                    "0%": { transform: "translateX(0%)" },
                    "100%": { transform: "translateX(-100%)" },
                },
            },
        },
    },
    plugins: [],
};
