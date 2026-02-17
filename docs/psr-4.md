# PSR-4 Autoloading

## How it works

PSR-4 maps PHP namespaces to directories. Composer handles the autoloading so you never need manual `require` statements.

### Configuration

In `composer.json`:

```json
{
  "autoload": {
    "psr-4": {
      "PortfolioCore\\": "src/"
    }
  }
}
```

This means: any class under the `PortfolioCore` namespace is resolved from the `src/` directory.

## Namespace-to-file mapping

| Namespace                          | File path                          |
| ---------------------------------- | ---------------------------------- |
| `PortfolioCore\Plugin`             | `src/Plugin.php`                   |
| `PortfolioCore\PostTypes\ProjectPostType` | `src/PostTypes/ProjectPostType.php` |
| `PortfolioCore\Fields\ProjectFields`      | `src/Fields/ProjectFields.php`     |
| `PortfolioCore\Admin\ProjectMetaBox`      | `src/Admin/ProjectMetaBox.php`     |
| `PortfolioCore\GraphQL\GraphQLConfig`     | `src/GraphQL/GraphQLConfig.php`    |
| `PortfolioCore\Security\Headless`         | `src/Security/Headless.php`        |

## Rules

1. **One class per file** — the file name must match the class name exactly (case-sensitive)
2. **Directory = sub-namespace** — `Admin\ProjectMetaBox` lives in `src/Admin/ProjectMetaBox.php`
3. **`declare(strict_types=1)`** — every file uses strict types
4. **Namespace declaration** — must match the directory path (`namespace PortfolioCore\Admin;`)

## Adding a new class

1. Create the file in the correct directory under `src/`
2. Set the namespace to match the path
3. Run `composer dump-autoload` (or `ddev composer dump-autoload`)
4. Wire it in `Plugin::init_modules()` if it needs to boot on plugin load

### Example

To add a `PortfolioCore\Taxonomies\SkillTaxonomy` class:

```
src/
└── Taxonomies/
    └── SkillTaxonomy.php
```

```php
<?php

declare(strict_types=1);

namespace PortfolioCore\Taxonomies;

final class SkillTaxonomy {
    // ...
}
```

Then run:

```bash
ddev composer dump-autoload
```

## Current directory structure

```
src/
├── Admin/
│   └── ProjectMetaBox.php
├── Fields/
│   └── ProjectFields.php
├── GraphQL/
│   └── GraphQLConfig.php
├── PostTypes/
│   ├── AbstractPostType.php
│   └── ProjectPostType.php
├── Security/
│   └── Headless.php
└── Plugin.php
```
