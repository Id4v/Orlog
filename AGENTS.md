# AGENTS.md - Guidelines for Agentic Coding

This document provides guidelines for agents working on the Orlog codebase.

## Project Overview

- **Framework**: Symfony 8.0 with PHP >= 8.4
- **Frontend**: Vite 6 with Tailwind CSS 4
- **Database**: Doctrine ORM 3.6
- **Package Managers**: Composer (PHP), npm/bun (JS)
- **PSR-4 Namespace**: `App\` maps to `src/`

## Build Commands

### PHP/Symfony
```bash
# Clear cache (required after code changes)
php bin/console cache:clear

# Install dependencies
composer install

# Run Doctrine migrations
php bin/console doctrine:migrations:migrate

# Generate Doctrine proxies
php bin/console doctrine:generate:proxies
```

### Frontend
```bash
# Development server
npm run dev

# Production build
npm run build
```

### Running PHP Commands
```bash
# Execute any Symfony console command
php bin/console <command>

# Run PHP built-in server
php -S localhost:8000 -t public/
```

## Code Style Guidelines

### PHP Conventions
- Always use `declare(strict_types=1);` at the top of PHP files
- Use PHP 8 attributes (e.g., `#[Route(...)]`) over annotations
- PSR-12 coding standard applies
- 4-space indentation (see `.editorconfig`)
- Opening brace on same line for classes/functions
- Alphabetically sort `use` statements within groups

### Naming Conventions
- **Classes**: PascalCase (e.g., `HomeController`, `DashboardController`)
- **Methods**: camelCase (e.g., `indexAction()` -> `__invoke()`)
- **Variables**: camelCase (e.g., `$userRepository`, `$httpClient`)
- **Constants**: UPPER_SNAKE_CASE
- **Controllers**: Suffix with `Controller` (e.g., `HomeController`)
- **Routes**: Use route names like `front_home`, `dashboard` (lowercase with underscores)
- **Template paths**: `bundle/controller/action.html.twig` pattern

### Imports
```php
// Symfony framework imports first
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Then third-party, then local
```

### Return Types & Type Hints
- Always use return types for public methods
- Use `?Type` for nullable types
- Use union types (`string|int`) for PHP 8+
- Strict typing enforced via `declare(strict_types=1);`

### Controller Pattern
```php
#[Route(path: '/', name: 'front_home')]
class HomeController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('front/home.html.twig');
    }
}
```

### Error Handling
- Let exceptions bubble up to Symfony's error handling
- Use `throw new \InvalidArgumentException()` for invalid input
- Return `Response` objects with appropriate status codes
- Use Symfony's `FlashBag` for user feedback

### File Organization
```
src/
  Controller/
    Front/
    Admin/
  Entity/
  Repository/
  Kernel.php
config/
  packages/
  services.yaml
tests/
```

### Template Conventions
- Twig templates in `templates/` directory
- Follow `controller/action.html.twig` naming
- Use Symfony's path functions: `{{ path('route_name') }}`
- Use `{% use ... %}` for reusable blocks

## Frontend Guidelines

### JavaScript/TypeScript
- Use ES modules
- Import from `node_modules` using package name
- Use Vite's auto-import for Symfony bundle

### CSS
- Tailwind CSS 4 for styling
- No custom CSS files (use Tailwind utility classes)
- Vite processes Tailwind automatically

## Testing

No test suite currently exists. When adding tests:
- Use PHPUnit for PHP tests
- Place tests in `tests/` directory
- Follow PSR-4: `App\Tests\Controller\Front\HomeControllerTest`
- Run specific tests: `./vendor/bin/phpunit --filter testName`

## Database

- Doctrine ORM 3.6 for database abstraction
- Use Doctrine migrations for schema changes
- Entity classes go in `src/Entity/`
- Repositories go in `src/Repository/`
- Run migrations: `php bin/console doctrine:migrations:migrate`

## Common Tasks

### Adding a New Controller
1. Create in appropriate namespace (`Controller/Front/` or `Controller/Admin/`)
2. Extend `AbstractController`
3. Use `#[Route]` attribute
4. Create corresponding template in `templates/`

### Adding a New Entity
1. Create in `src/Entity/`
2. Use Doctrine ORM annotations or attributes
3. Create repository if needed
4. Generate migration: `php bin/console make:migration`

### Running Code Analysis
```bash
# PHP lint (syntax check)
php -l src/Controller/Front/HomeController.php

# Composer validate
composer validate
```

## Important Notes

- Always clear cache after route/controller changes
- This is a Symfony 8.0 project (not 7.x)
- PHP 8.4+ required
- No tests exist - write tests when adding features
- No linting configured - follow PSR-12 manually
- Tailwind CSS 4 uses CSS-first configuration
- Pour plus d'infos, va voir dans le fichier .agents/BRIEF.md
