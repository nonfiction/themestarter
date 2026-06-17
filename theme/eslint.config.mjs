import compat from 'eslint-plugin-compat';

export default [
  {
    ignores: [
      '../plugins/**/build/**',
      '../plugins/**/dist/**',
      '../plugins/**/node_modules/**',
      '../plugins/**/vendor/**',
      'dist/**',
      'vendor/**',
    ],
  },
  {
    files: ['../plugins/**/*.js', 'app/**/*.js', 'config/**/*.js', 'vite.config.js'],
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
