<?php

return [
    'enabled' => true,

    /*
     * The url that will return all the metrics.
     */
    'url' => 'prometheus',

    /*
     * The middleware that will be applied to the url above
     */
    'middleware' => [
        Spatie\Prometheus\Http\Middleware\AllowIps::class,
    ],

    /*
     * Only these IP's will be allowed to see the metrics.
     * When set to `null` all IP's are allowed.
     */
    'allowed_ips' => [

    ],

    'actions' => [
        'render_collectors' => Spatie\Prometheus\Actions\RenderCollectorsAction::class,
    ],
];
