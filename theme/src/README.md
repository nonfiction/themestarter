# Project-local PHP extensions

`theme/src` is for PHP classes that belong to this consuming theme, not to the shared `nonfiction/theme` 
package found at [https://github.com/nonfiction/theme](https://github.com/nonfiction/theme)

## How it works

- Composer autoloading makes classes in `theme/src` available to PHP.
- Autoloading does **not** change which class WordPress, Timber, or the shared package uses at runtime.
- To actually use a local class, you still need to wire it in with the relevant hook, filter, or factory.

So, adding `nf\Something\LocalClass` under `theme/src` means the class can be loaded, but it will not automatically replace `Nonfiction\Theme\...` classes from the shared package.

## Where code should live

- `theme/src`: project-specific PHP extensions, overrides, and small local subclasses.
- `nonfiction/theme`: reusable framework code that should work across projects.
- `theme/app`: theme bootstrapping, block/CPT registration, template wiring, and page-specific behavior.
- `theme/config`: runtime WordPress configuration hooks and filters.

## Example: Timber menu items

If this project needs custom Timber menu item behavior, a local subclass can live at `nf\Timber\MenuItem`:

```php
class MenuItem extends \Nonfiction\Theme\Timber\MenuItem {
}
```

That class is only useful once Timber is told to use it. The filter is necessary because Timber otherwise keeps using its default menu item class:

```php
add_filter('timber/menuitem/class', function ($class, $item, $menu) {
    return \nf\Timber\MenuItem::class;
}, 10, 3);
```

This file can stay as a placeholder until the project needs the override.
