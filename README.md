# juanpredev-cms

Headless WordPress CMS for my personal portfolio. API-only via WPGraphQL — no frontend rendering. The Next.js frontend consumes the GraphQL API from Vercel.

## Stack

| Layer      | Tech                        |
|------------|-----------------------------|
| CMS        | WordPress 6.9.1             |
| API        | WPGraphQL 2.9.0             |
| PHP        | 8.2                         |
| Local dev  | DDEV (nginx-fpm + MariaDB)  |
| Production | Dokploy (Docker Compose)    |
| Frontend   | Next.js on Vercel           |

## Local Setup

```bash
ddev start
ddev wp core download
ddev wp core install --url=https://juanpredev-cms.ddev.site \
  --title="JuanPreDev Portfolio" --admin_user=admin \
  --admin_password=admin --admin_email=admin@example.com
ddev wp rewrite structure '/%postname%/' --hard
ddev wp plugin install wp-graphql --activate
ddev composer install --working-dir=wp-content/plugins/portfolio-core
ddev wp plugin activate portfolio-core
ddev wp rewrite flush
```

## URLs (DDEV)

- Admin: `https://juanpredev-cms.ddev.site/wp-admin`
- GraphQL: `https://juanpredev-cms.ddev.site/graphql`

## Plugin: portfolio-core

All custom logic lives in `wp-content/plugins/portfolio-core/`:

```
portfolio-core/
├── portfolio-core.php              # Bootstrap
├── composer.json                   # PSR-4 autoloading
├── src/
│   ├── Plugin.php                  # Singleton orchestrator
│   ├── PostTypes/
│   │   ├── AbstractPostType.php    # Base class with GraphQL defaults
│   │   └── ProjectPostType.php     # Project CPT
│   ├── GraphQL/
│   │   └── GraphQLConfig.php       # CORS + debug config
│   └── Security/
│       └── Headless.php            # XML-RPC, comments, redirect, REST strip
```

## Content Model

| Type    | Slug      | GraphQL          | Status      |
|---------|-----------|------------------|-------------|
| Project | `project` | `Project/Projects` | Implemented |
| Post    | `post`    | `Post/Posts`       | Built-in    |
| Skill   | —         | —                  | Planned     |
| Experience | —      | —                  | Planned     |

## GraphQL Queries

```graphql
# Projects
{ projects { nodes { id title excerpt content } } }

# Posts
{ posts { nodes { id title excerpt date } } }
```

## Security (Headless Mode)

- XML-RPC disabled
- Comments disabled
- Frontend redirects to `/wp-admin/`
- REST `/wp/v2/users`, `/comments`, `/settings` endpoints stripped
- CORS configurable via `GRAPHQL_CORS_ORIGIN` env var
