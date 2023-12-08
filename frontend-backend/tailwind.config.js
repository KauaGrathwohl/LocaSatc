/** @type {import('tailwindcss').Config} */

const { fontFamily } = require("tailwindcss/defaultTheme");

module.exports = {
  content: [
    "./Plugin/**/*.ctp",
    "./Plugin/**/*.php",
    "./View/**/*.ctp",
    "./View/**/*.php",
    "./webroot/src/js/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    screens: {
      xs: "375px",
      sm: "640px",
      md: "768px",
      lmd: "990px",
      lg: "1108px",
      xl: "1366px",
      "2xl": "1536px",
      "3xl": "1890px",
    },
    colors: {
      transparent: "transparent",
      current: "currentColor",
      white: "#FFF",
      black: "#000",
      gray: "#DDD",
      primary: "#3EA3D4",
      success: "#25D366",
      info: "#D7F9FB",
      warning: "#FAB71B",
      danger: "#D01235",
      green: "#25D366",
      yellow: {
        medium: "#EFC428", // Projetos - Anjos do Futsal - Amarelo
        DEFAULT: "#FAB71B", // Logo - Amarelo
        dark: "#F6A834", // Aquarelas - Amarelo
      },
    },
    backgroundSize: {
      auto: "auto",
      cover: "cover",
      contain: "contain",
      full: "100%",
    },
    aspectRatio: {
      auto: "auto",
      square: "1 / 1",
      video: "16 / 9",
      1: "1",
      2: "2",
      3: "3",
      4: "4",
      5: "5",
      6: "6",
      7: "7",
      8: "8",
      9: "9",
      10: "10",
      11: "11",
      12: "12",
      13: "13",
      14: "14",
      15: "15",
      16: "16",
    },
    extend: {
      fontFamily: {
        ...fontFamily,
        sans: ["'Poppins'", ...fontFamily.sans],
      },
      lineHeight: {
        "extra-loose": "2.5",
        12: "3.25rem",
      },
      container: {
        center: true,
        padding: {
          DEFAULT: "0.75rem",
          sm: "0.75rem",
          md: "1rem",
          lg: "0.5rem",
          xl: "0",
          "2xl": "0",
        },
      },
    },
  },
  variants: {
    extend: {
      backgroundColor: ["even"],
    },
  },
  plugins: [require("@tailwindcss/forms"), require("@tailwindcss/aspect-ratio")],
};
