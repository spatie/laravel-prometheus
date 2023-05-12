<?php

// config for Spatie/Prometheus
return [
    'enabled' => true,

    'url' => 'prometheus',

    'allowed_ips' => [

    ],

    'middleware' => [
        \Spatie\Prometheus\Http\Middleware\AllowIps::class,
    ],

    'actions' => [
        'render_collectors' => Spatie\Prometheus\Actions\RenderCollectorsAction::class,
    ],
];
