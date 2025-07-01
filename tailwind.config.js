/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{html,js,ts,tsx,css}"], // Pastikan path sesuai
  theme: {
    extend: {
      colors: {
        "primary-black": "#0A0723",
        "primary-green": "#2F6A62",
        "primary-grey": "#EAECEE",
        "primary-red": "#EF372B",
        "primary-text-grey": "#87898C",
        "primary-light-green": "#E0EAE8",
        "primary-light-red": "#FFE3E1",
        "primary-text-secondary": "#6A6F7C",
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
