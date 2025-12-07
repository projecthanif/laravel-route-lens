<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Projecthanif\RouteScope\Controllers\RouteScopeController;

Route::get('/', [RouteScopeController::class, 'index'])->name('index');
