<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Route Lens Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file is used to control the behavior of the
    | Laravel Route Lens package.
    |
    */

    'enabled' => env('ROUTESCOPE_ENABLED', app()->environment('local', 'development')),

    'prefix' => env('ROUTESCOPE_PREFIX', 'routescope'),

    'excluded_patterns' => [
        'routescope',
        '_ignition',
        'sanctum/csrf-cookie',
        'telescope',
        '__execute-laravel-error-solution',
    ],
];
