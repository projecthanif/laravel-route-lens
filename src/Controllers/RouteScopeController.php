<?php

declare(strict_types=1);

namespace Projecthanif\RouteScope\Controllers;

use Illuminate\Routing\Controller;
use Projecthanif\RouteScope\Facades\RouteScope;

final class RouteScopeController extends Controller
{
    public function index()
    {
        $routes = RouteScope::getAllRoutes();

        $apiRoutes = $routes["apiRoutes"]->toArray();
        $webRoutes = $routes["webRoutes"]->toArray();

        return view("routescope::routescope", [
            "apiRoutes" => $apiRoutes,
            "webRoutes" => $webRoutes,
        ]);
    }
}
