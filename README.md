# nonfiction theme-starter

<img width="300" align="right" src="theme/screenshot.png">

Starter template for nonfiction WordPress themes using [`nf`](https://github.com/nonfiction/nf), [nonfiction/theme](https://github.com/nonfiction/theme), Timber, Twig, Composer, and Vite.

This repository is intended to be copied for new agency WordPress theme projects. The repo-level workflow is `nf` first; raw Composer, npm, Docker, and WP-CLI commands are implementation details underneath that command surface.

## Quick Start

Enter the development shell from the repository root:

```sh
nix develop
```

Refresh theme dependencies through `nf`:

```sh
nf theme composer
nf theme npm
```

Start the local WordPress environment:

```sh
nf env up
```

Seed starter content, set Post name permalinks, create the primary menu, and remove Hello Dolly:

```sh
nf theme seed
```

Show local URLs, ports, paths, and env metadata:

```sh
nf env show
```

Watch theme assets during development:

```sh
nf theme watch
```

Run WP-CLI inside the local environment:

```sh
nf env wp -- plugin list
nf env wp -- post-type list
```

## nf Workflow

`nf.json` is the project manifest. It records the project slug, WordPress theme path, theme slug, local env settings, configured plugins, artifact path, remotes, and theme tasks.

Useful commands:

```sh
treefmt
nix fmt
nf theme tasks
nf theme format
nf theme lint
nf theme test
nf theme build
nf theme package
nf theme release
nf theme seed
```

Local environment commands:

```sh
nf env up
nf env down
nf env logs
nf env shell
nf env wp -- <args>
nf env plugins list
nf env plugins status
nf env plugins install
nf env snapshot list
```

Remote and release commands are configured per project:

```sh
nf remote list
nf remote add production
nf theme deploy production --dry-run
nf theme deploy production
nf theme rollback production --dry-run
```

`nf theme package` zips the files that already exist. It does not run Composer, npm, or the asset build first. Use `nf theme release` for the normal build, check, and package sequence.

## Theme Tasks

Configured tasks live in `nf.json`.

Current tasks:

```sh
nf theme build    # Build Vite assets
nf theme composer # Update Composer dependencies and optimized autoload
nf theme format   # Format authored PHP files
nf theme lint     # Run PHP checks and JavaScript lint
nf theme npm      # Update npm development dependencies
nf theme release  # Build, lint, and package the theme artifact
nf theme seed     # Seed starter WordPress content
nf theme test     # Alias for all theme checks
nf theme watch    # Watch Vite assets
```

## Project Structure

Theme source lives in `theme/`.

Important paths:

```text
theme/functions.php        Theme bootstrap, imports, Timber context, and Twig extensions
theme/app/                 Native views, custom post types, and custom blocks
theme/app/views/           Global Twig templates and native post/page helpers
theme/app/blocks/          Reusable custom blocks
theme/app/<cpt>/           Custom post type modules
theme/config/              WordPress configuration hooks
theme/src/                 Theme PHP classes
theme/dist/                Built Vite assets
theme/vendor/              Composer dependencies
```

`theme/functions.php` initializes the shared [nonfiction/theme](https://github.com/nonfiction/theme) app layer, imports native view helpers, custom post type modules, reusable blocks, nested CPT blocks, and config files, and defines global Timber context and Twig extensions.

Use `theme/app/<cpt>/` for custom post types. Native WordPress post/page view helpers belong in `theme/app/views/`, not in `theme/app/post/` or `theme/app/page/`.

## Formatting And Tooling

`nix develop` exposes the repo tooling used by this starter: `nf`, `treefmt`, PHP 8.3, Composer, PHP-CS-Fixer, PHPStan, PHPactor, Node 24, Docker client, and Git.

Format everything configured in `treefmt.nix`:

```sh
treefmt
```

`nix fmt` uses the same treefmt wrapper.

The repo-level formatter runs Alejandra for Nix files, PHP-CS-Fixer for authored theme PHP, and Prettier for Markdown plus theme CSS, HTML, JavaScript, JSON, Twig, YAML, and YML files. The Prettier wrapper runs from `theme/` so it can use `theme/.prettierrc.json`, `theme/.prettierignore`, and the project-local `@zackad/prettier-plugin-twig` install.

If treefmt reports a missing Prettier binary, install theme node dependencies first:

```sh
nf theme npm
```

PHPactor is available in the dev shell. Its project config lives at `theme/.phpactor.json` and points the language server at WordPress stubs, the local PHP-CS-Fixer config, 2-space indentation, and ignored docblock diagnostics that are noisy for WordPress-style code.

## PHP Formatting

PHP-CS-Fixer is the canonical PHP formatter for this starter. The project policy lives in `theme/.php-cs-fixer.dist.php` and uses PSR-12 plus agency preferences: 2-space PHP indentation, short arrays, ordered imports, unused import removal, single quotes where appropriate, trailing commas in multiline structures, readable spacing, and no Yoda-style requirement.

Format PHP:

```sh
nf theme format
```

Check PHP style without modifying files:

```sh
composer --working-dir=theme check:php-style
```

The fixer is scoped to authored theme PHP: `app`, `config`, `src`, and root theme PHP files such as `functions.php` and `index.php`. Generated and dependency directories such as `vendor`, `node_modules`, `assets/dist`, `dist`, and build artifacts are excluded.

Neovim should use the project-local binary at `theme/vendor/bin/php-cs-fixer` and automatically pick up `theme/.php-cs-fixer.dist.php` when editing files in this project. CI should run the same Composer scripts rather than using a global formatter configuration.

Run the full configured check path:

```sh
nf theme test
```

## Local WordPress Setup

Start or repair the local environment:

```sh
nf env up
```

Set pretty permalinks after a fresh env, if needed:

```sh
nf theme seed
```

The seed task sets permalinks to Post name, removes Hello Dolly if installed, creates starter pages, creates a `Primary` menu with the slug `primary`, assigns it to the `primary` theme location, seeds a Block Examples page, and sets Home as the static front page.

Inspect local WordPress state:

```sh
nf env wp -- theme list
nf env wp -- post-type list
nf env wp -- plugin list
```

Configured plugins are listed in `nf.json` under `wordpress.plugins`. This list is bootstrap intent, not a complete plugin lifecycle manager.

Seeded QA routes:

```text
/
/about/
/services/
/block-examples/
/resources/
/resources/resource-one/
/resources/resource-two/
/contact/
/articles/
/articles/starter-article/
/search/starter/
/not-a-real-page/
```

## Starter Blocks

Generic reusable blocks live in `theme/app/blocks/`.

Current starter block set:

- `nf/banner`: full-width page introduction with a background image, heading, and optional supporting text
- `nf/aside`: supporting callout with a heading and nested content
- `nf/grid`: Card Grid, a responsive container for repeated content
- `nf/card`: card intended for use inside `nf/grid`
- `nf/accordion`: Content Accordion, grouped expandable content
- `nf/accordion-item`: single item inside `nf/accordion`

Keep this set compact. Client-specific blocks should either be deleted before starting a new project or moved under `theme/app/<cpt>/blocks/` when they belong to a custom post type.

## Starting A Client Theme

When copying this starter for a client project, update these values before doing feature work:

- `nf.json` `project.slug`
- `nf.json` `wordpress.theme_slug`
- `nf.json` `artifact.path`
- `theme/style.css` `Theme Name`, `Text Domain`, `Description`, and `Version`
- `theme/package.json` `name`, `version`, and repository URL
- `theme/composer.json` `name` and `description`
- README title and project description
- Any block names, labels, or namespaces that should become client-specific
- Any configured plugins in `nf.json` that are not appropriate for the project

The theme slug matters because `nf theme package` uses `wordpress.theme_slug` as the zip root directory, even when source files live in `wordpress.theme_path`.

## Release Checklist

Before packaging or deploying a theme release:

```sh
nf theme release
```

For remote deploys, preview first:

```sh
nf theme deploy production --dry-run
```

Then deploy when the plan is correct:

```sh
nf theme deploy production
```
