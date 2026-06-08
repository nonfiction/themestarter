import compat from 'eslint-plugin-compat';

export default [
  {
    ignores: ['dist/**', 'vendor/**'],
  },
  {
    files: ['app/**/*.js', 'config/**/*.js', 'vite.config.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      parserOptions: {
        ecmaFeatures: {
          jsx: true,
        },
      },
    },
    plugins: {
      compat,
    },
    rules: {
      'compat/compat': 'warn',
    },
  },
];
