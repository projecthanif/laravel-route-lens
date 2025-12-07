# RouteScope

**A powerful route inspection tool for Laravel developers.**

RouteScope gives you instant visibility into your application's routing layer. Stop guessing which routes exist, what middleware they use, or where they're defined. See everything at a glance with an elegant dashboard or query routes programmatically.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/projecthanif/routescope.svg?style=flat-square)](https://packagist.org/packages/projecthanif/routescope)
[![Total Downloads](https://img.shields.io/packagist/dt/projecthanif/routescope.svg?style=flat-square)](https://packagist.org/packages/projecthanif/routescope)

## Why RouteScope?

### 🔍 **Instant Route Visibility**
Ever wondered "Does this route actually exist?" or "What middleware is protecting this endpoint?" RouteScope answers these questions instantly with a clean, organized view of every route in your application.

### 🎯 **Smart Organization**
Routes are automatically categorized into API and web routes, making it easy to understand your application's structure at a glance. No more scrolling through `php artisan route:list` output.

### 🚀 **Developer Productivity**
- **Debug faster** - Quickly identify routing issues and middleware conflicts
- **Onboard easier** - New team members can explore the API surface in minutes
- **Document better** - Generate route documentation programmatically
- **Refactor confidently** - See the full scope of changes when restructuring routes

### 🛡️ **Production-Safe**
Built with safety in mind. RouteScope automatically disables itself in production environments and can be installed as a dev dependency to keep your production builds lean.

## Installation

Install as a development dependency:

```bash
composer require projecthanif/routescope --dev
```

The package auto-registers via Laravel's service provider discovery. No additional setup required!

## Quick Start

Visit the dashboard in your browser:

```
http://localhost/routescope
```

That's it! You'll see all your routes organized, searchable, and ready to explore.

## Configuration

Need to customize? Publish the configuration file:

```bash
php artisan vendor:publish --tag=routescope-config
```

Edit `config/routescope.php`:

```php
return [
    // Only enable in local/development environments
    'enabled' => env('ROUTESCOPE_ENABLED', app()->environment('local', 'development')),
    
    // Customize the dashboard URL
    'prefix' => env('ROUTESCOPE_PREFIX', 'routescope'),
    
    // Hide routes you don't want to see (debug tools, internal routes, etc.)
    'excluded_patterns' => [
        'routescope',
        '_ignition',
        'sanctum/csrf-cookie',
        'telescope',
        'horizon',
    ],
];
```

## Features

### 📊 Interactive Dashboard
A beautiful, responsive interface that displays:
- HTTP methods (GET, POST, PUT, DELETE, PATCH)
- Route URIs and named routes
- Controller actions or closure definitions
- Applied middleware chains
- Quick search and filtering

### 🔌 Programmatic Access
Query routes from your code using the facade or dependency injection:

```php
use Projecthanif\RouteScope\Facades\RouteScope;

$routes = RouteScope::getAllRoutes();

// Returns:
[
    'apiRoutes' => [...],  // All /api/* routes
    'webRoutes' => [...]   // All other routes
]
```

### 🎨 Smart Categorization
Routes are automatically organized:
- **API Routes**: Everything under `/api/*`
- **Web Routes**: Your standard web application routes

### ⚙️ Flexible Filtering
Exclude routes you don't care about:
- Debug tools (Telescope, Ignition)
- Internal Laravel routes
- Third-party package routes
- Custom patterns you define

### 🔒 Type-Safe
Built with strict typing and comprehensive type hints for a better development experience with IDE autocomplete and static analysis tools.

## Usage Examples

### View All Routes in a Custom Command

```php
use Projecthanif\RouteScope\Facades\RouteScope;

class InspectRoutes extends Command
{
    public function handle()
    {
        $all = RouteScope::getAllRoutes();
        
        $this->info('API Routes:');
        foreach ($all['apiRoutes'] as $route) {
            $this->line("  {$route['method']} {$route['path']}");
        }
        
        $this->info('Web Routes:');
        foreach ($all['webRoutes'] as $route) {
            $this->line("  {$route['method']} {$route['path']}");
        }
    }
}
```

### Find Routes with Specific Middleware

```php
use Projecthanif\RouteScope\Services\RouteScopeService;

$service = app(RouteScopeService::class);
$routes = $service->getAllRoutes();

$authRoutes = collect($routes['webRoutes'])
    ->filter(fn($route) => in_array('auth', $route['middleware']))
    ->all();
```

### Generate API Documentation

```php
use Projecthanif\RouteScope\Facades\RouteScope;

$routes = RouteScope::getAllRoutes();

$markdown = "# API Endpoints\n\n";

foreach ($routes['apiRoutes'] as $route) {
    $markdown .= "### {$route['method']} {$route['path']}\n\n";
    $markdown .= "**Controller**: `{$route['source']}`\n";
    $markdown .= "**Middleware**: " . implode(', ', $route['middleware']) . "\n\n";
}

file_put_contents('api-docs.md', $markdown);
```

### Dependency Injection in Controllers

```php
use Projecthanif\RouteScope\Services\RouteScopeService;

class RouteAnalysisController extends Controller
{
    public function __construct(
        private RouteScopeService $routeScope
    ) {}
    
    public function index()
    {
        $routes = $this->routeScope->getAllRoutes();
        
        return view('admin.routes', compact('routes'));
    }
}
```

## API Reference

### `RouteScope::getAllRoutes(): array`

Returns all routes organized into API and Web categories.

**Response Structure:**

```php
[
    'apiRoutes' => [
        [
            'method' => 'GET|POST|PUT|DELETE|PATCH',
            'path' => '/api/users',
            'name' => 'users.index',  // or null if unnamed
            'source' => 'App\Http\Controllers\UserController::index',
            'middleware' => ['api', 'auth:sanctum'],
        ],
        // ... more routes
    ],
    'webRoutes' => [
        [
            'method' => 'GET',
            'path' => '/dashboard',
            'name' => 'dashboard',
            'source' => 'App\Http\Controllers\DashboardController::show',
            'middleware' => ['web', 'auth'],
        ],
        // ... more routes
    ]
]
```

## Environment Variables

```env
# Enable/disable the package (automatically disabled in production)
ROUTESCOPE_ENABLED=true

# Customize the dashboard URL prefix
ROUTESCOPE_PREFIX=routescope
```

## Production Safety

RouteScope is designed to be safe by default:

1. **Auto-disabled in production** - The default configuration only enables RouteScope in local/development environments
2. **Dev dependency** - Install with `--dev` to exclude from production builds
3. **Lightweight** - Zero runtime overhead when disabled
4. **No database** - Purely reads from Laravel's route collection

To ensure it's disabled in production, add to `.env.production`:

```env
ROUTESCOPE_ENABLED=false
```

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Illuminate/Support package

## Use Cases

### 🐛 **Debugging**
"Why isn't my route working?" - See instantly if the route exists, what middleware is blocking it, and where it's defined.

### 📚 **Documentation**
Generate comprehensive route documentation for your team or API consumers programmatically.

### 👥 **Onboarding**
New developers can explore your application's API surface without diving into route files.

### 🔍 **Auditing**
Quickly identify which routes lack authentication, have duplicate definitions, or use deprecated middleware.

### 🏗️ **Refactoring**
When restructuring your application, see the full scope of route changes in one place.

## Testing

```bash
composer test           # Run all tests
composer test:lint      # Code style checks
composer test:types     # Static analysis
composer test:unit      # Unit tests
```

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](./CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email iamustapha213@gmail.com instead of using the issue tracker.

## Credits

- [Ibrahim Mustapha](https://github.com/projecthanif)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE.md](./LICENSE.md) for more information.

---

**RouteScope** - See your routes clearly. Debug confidently. Build faster.
