import globals from "globals";
import js from "@eslint/js";

export default [
  {
    files: ["**/*.js"],
    languageOptions: {
      ecmaVersion: 2021,
      sourceType: "module",
      globals: {
        ...globals.browser,
        ...globals.node
      }
    },
    plugins: {},
    rules: {
      ...js.configs.recommended.rules,
      // Add any custom rules here
    }
  },
  {
    ignores: ["**/vendor/**", "**/node_modules/**"]
  }
];