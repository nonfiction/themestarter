# nonfiction theme-starter

Starter template for nonfiction wordpress themes using timber, twig, composer, and modern front-end tooling.

## Development

Enter the Nix development shell from the repository root:

```sh
nix develop
```

Install the theme dependencies from the theme directory:

```sh
cd theme
composer install
npm install
```

The Nix shell provides PHP, Composer, Node, `nf`, and `php-cs-fixer`. Composer installs the project-local PHP tooling used by scripts and editors.

## PHP Formatting

PHP-CS-Fixer is the canonical PHP formatter for this starter. The project policy lives in `theme/.php-cs-fixer.dist.php` and is intentionally based on PSR-12 plus agency preferences: short arrays, ordered imports, unused import removal, single quotes where appropriate, trailing commas in multiline arrays/arguments/parameters, readable spacing, and no Yoda-style requirement.

Format PHP from `theme/`:

```sh
composer format:php
```

Check PHP style without modifying files:

```sh
composer check:php-style
```

The fixer is scoped to the theme's authored PHP code: `app`, `config`, `src`, and root theme PHP files such as `functions.php` and `index.php`. Generated and dependency directories such as `vendor`, `node_modules`, `assets/dist`, `dist`, and build artifacts are excluded.

Neovim should use the project-local binary at `theme/vendor/bin/php-cs-fixer` and automatically pick up `theme/.php-cs-fixer.dist.php` when editing files in this project. CI should run the same Composer scripts rather than using a global formatter configuration.

## WordPress Guardrails

PHPCS/WPCS is included only as a secondary WordPress-aware lint guardrail, not as the formatter and not as the owner of code style. Its narrow ruleset lives in `theme/phpcs.xml.dist` and focuses on security checks such as escaping, nonce verification, and input validation/sanitization.

Run the WordPress guardrail check from `theme/`:

```sh
composer lint:php-wp
```
