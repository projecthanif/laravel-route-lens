<?php

declare(strict_types=1);

namespace Projecthanif\RouteScope\Facades;

use Illuminate\Support\Facades\Facade;
use Projecthanif\RouteScope\Services\RouteScopeService;

/**
 * @method static array getAllRoutes()
 *
 * @see RouteScopeService
 */
final class RouteScope extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RouteScopeService::class;
    }
}
