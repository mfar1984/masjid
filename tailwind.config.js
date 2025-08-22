/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'figtree': ['Figtree', 'Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        'sans': ['Figtree', 'Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        'xs': '8px',
        'sm': '10px', 
        'base': '12px',
      },
    },
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: ["corporate"],
  },
} 