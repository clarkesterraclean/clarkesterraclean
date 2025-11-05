/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        'carbon-dark': '#0f0f0f',
        'carbon-light': '#f5f5f5',
        'eco-green': '#4ade80',
        'text-body': '#d4d4d4',
        'text-dark': '#1a1a1a',
      },
      container: {
        center: true,
        padding: '1rem',
      },
    },
  },
  plugins: [],
}

