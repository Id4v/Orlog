# Repository Guidelines

## Project Structure & Module Organization
The backend follows Symfony conventions under `src/` (`App\\` namespace). Main areas are `src/Controller/Front`, `src/Controller/Admin`, `src/Entity`, and `src/Repository`. Templates are in `templates/` (for example, `templates/front/home.html.twig`). Frontend source files are in `assets/` and are built with Vite into `public/`. App and package configuration lives in `config/`, and database schema changes are tracked in `migrations/`. Add PHPUnit tests in `tests/`.

## Build, Test, and Development Commands
- `composer install`: install PHP dependencies.
- `npm install` or `bun install`: install frontend dependencies.
- `php bin/console cache:clear`: clear Symfony cache after route/controller/config updates.
- `php bin/console doctrine:migrations:migrate`: run database migrations.
- `npm run dev`: start the Vite development server.
- `npm run build`: produce production frontend assets.
- `php -S localhost:8000 -t public/`: run a local PHP server.

## Coding Style & Naming Conventions
Use `declare(strict_types=1);` in PHP files. Follow PSR-12 with 4-space indentation and same-line braces. Prefer PHP 8 attributes (e.g., `#[Route(...)]`) instead of annotations. Keep `use` imports ordered and grouped cleanly. Naming rules: classes in PascalCase (`DashboardController`), methods/variables in camelCase, constants in UPPER_SNAKE_CASE, routes in lowercase snake_case (`front_home`), and controllers suffixed with `Controller`.

## Testing Guidelines
PHPUnit is the test framework for backend code. There is currently no broad test suite, so add focused tests with each feature or bug fix. Use PSR-4 test names such as `App\\Tests\\Controller\\Front\\HomeControllerTest`. Run all tests with `./vendor/bin/phpunit`, or target a case with `./vendor/bin/phpunit --filter testName`.

## Commit & Pull Request Guidelines
Recent history shows short, focused commit subjects (for example, `Login link + Rate limiter`). Keep commits scoped to one change and use clear imperative wording. For pull requests, include:
- a concise change summary,
- linked issue/ticket when available,
- migration notes for schema changes,
- screenshots for template/UI updates,
- reviewer run steps (cache clear, migrations, build).

## Security & Configuration Tips
Do not commit secrets. Store environment-specific values in `.env.local`. Review security-sensitive changes in `config/packages/security.yaml` and rate-limit settings in `config/packages/rate_limiter.yaml` before merging.

## Product Scope Reference
For MVP feature scope, business rules, and workflows, use `.agents/BRIEF.md` as the source of truth.
