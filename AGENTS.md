# Themestarter Engineering Guide

## Purpose

This repository is nonfiction's reusable greenfield WordPress baseline. Keep it
small, current, copyable, and easy to trim. It is not a client production site
and should not accumulate client-specific content or infrastructure.

## Sources Of Truth

- `nf.json` defines the current project, theme, plugins, tasks, defines, and
  Linode validation remote.
- `theme/functions.php` defines imports, Twig views, theme support, menus, and
  asset loading.
- `theme/composer.json`, `theme/package.json`, and `theme/vite.config.js` define
  the theme toolchain.
- Current `nf` help and documentation outrank copied or historical command
  examples.

## Architecture

- The repository theme is `theme/`; it is the package and deploy unit.
- `nonfiction/theme` provides shared infrastructure, not client design or
  content.
- `theme/app/views/` holds shared templates and native post/page helpers.
- `theme/app/blocks/<block>/` holds reusable colocated blocks.
- `theme/app/<feature>/` holds a post type or feature and its local views,
  blocks, styles, and scripts.
- `theme/config/` holds direct WordPress hooks and admin behavior.
- `theme/src/` is only for genuinely reusable project-local PHP classes.
- `plugins/agency-credit/` demonstrates a durable repository plugin boundary.

Preserve the predictable import paths in `theme/functions.php`. Add new files
where existing imports discover them rather than creating parallel application
trees.

## Starter Contracts

- The first configured theme is active and must remain the repo theme.
- The starter includes `nf/accordion`, `nf/accordion-item`, `nf/aside`,
  `nf/banner`, `nf/card`, and `nf/grid` examples.
- The Article module, seed data, fallback Twenty Twenty themes, agency-credit
  plugin, and example defines are demonstrations, not mandatory client
  architecture.
- `nf theme seed` must remain safe to rerun and must not duplicate its content.
- Built assets are represented by the custom Vite manifest consumed by
  `nonfiction/theme`.

When changing a starter contract, test both this repository and the experience
of copying and trimming it for a new project.

## Defines And Private State

`AGENCY_NAME` is commit-safe. `MY_SECRET` is an encrypted example stored through
`nf.age`. Never expose decrypted values. Copied projects must deliberately
replace or remove the example secret and should declare only required defines.

Private plugin ZIPs, licenses, uploads, databases, local environments, generated
dependencies, and release archives are not source code.

## Verification

Run from the repository root:

```sh
nf theme check
nf theme build
nf env up
nf theme seed
nf theme package --dry-run
```

After seed changes, verify the home page, menu, block examples, Article archive
and single, search, and 404 routes. Check the editor and frontend for block
errors, console failures, and missing assets.

Packaging installs production Composer dependencies in staging but does not
build Vite assets. Build before packaging and verify the archive contains the
theme runtime and built assets without development or private state.

## Remote Safety

`linode -> themestarter.linode1:live` is a shared demo and validation target,
not client production. Confirm target identity and backup state before writes.
Theme deployment does not move the database, uploads, aliases, plugin settings,
licenses, or secrets.

Do not add a client production remote, copy client data into the demo, or treat
the demo's state as a universal starter requirement.
