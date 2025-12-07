<?php

declare(strict_types=1);

namespace Projecthanif\RouteScope\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class RouteScopeProvider extends ServiceProvider
{
    public const CONFIG_PATH = __DIR__ . "/../../config/routescope.php";

    /**
     * Register services into the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, "routescope");
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->registerRoutes();
    }

    /**
     * Publish the configuration file.
     */
    private function publishConfig(): void
    {
        $this->publishes(
            [
                self::CONFIG_PATH => config_path("routescope.php"),
            ],
            "routescope-config",
        );
    }

    /**
     * Register package routes.
     */
    private function registerRoutes(): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        Route::group(
            [
                "prefix" => config("routescope.prefix", "routescope"),
                "namespace" => "Projecthanif\\RouteScope\\Controllers",
            ],
            fn() => $this->loadRoutesFrom(__DIR__ . "/../routes/web.php"),
        );

        $this->loadViewsFrom(__DIR__ . "/../../resources/views", "routescope");

        $this->publishes(
            [
                __DIR__ . "/../../resources/views" => resource_path("views/vendor/routescope"),
            ],
            "views",
        );
    }

    /**
     * Check if the package is enabled.
     */
    private function isEnabled(): bool
    {
        return (bool) config("routescope.enabled", app()->environment("local", "development"));
    }
}
