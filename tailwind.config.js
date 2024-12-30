/** @type {import('tailwindcss').Config} */
const myConfig = {
  content: ["./app/**/*.{html,js,php}", "./public/**/*.{css,js}"],
  theme: {
    extend: {
      textColor: {
        primary: "#E48C44",
        secondary: "#FFC700",
        danger: "#e3342f",
      },
      borderColor: {
        primary: "#FFC700",
        secondary: "#1B2D44",
      },
      backgroundColor: {
        primary: "#14244B",
        "dark-blue": "#045174",
      },
      borderWidth: {
        3: "3px",
      },
    },
  },
  plugins: [require("daisyui")],
  mode: "jit",
  prefix: "tw-",
  darkMode: "class",
  daisyui: {
    themes: ["light"],
  },
};

module.exports = myConfig;
