<?php

return [
    'enabled' => true,

    /*
     * The urls that will return metrics.
     */
    'urls' => [
        'default' => 'prometheus',
    ],

    /*
     * Only these IP's will be allowed to visit the above urls.
     * All IP's are allowed when empty.
     */
    'allowed_ips' => [
        // '1.2.3.4',
    ],

    /*
     * This is the default namespace that will be
     * used by all metrics
     */
    'default_namespace' => 'app',

    /*
     * The middleware that will be applied to the urls above
     */
    'middleware' => [
        Spatie\Prometheus\Http\Middleware\AllowIps::class,
    ],

    /*
     * You can override these classes to customize low-level behaviour of the package.
     * In most cases, you can just use the defaults.
     */
    'actions' => [
        'render_collectors' => Spatie\Prometheus\Actions\RenderCollectorsAction::class,
    ],

    /**
     * Allow storage to be wiped after a render of data in metrics controller.
     */
    'wipe_storage_after_rendering' => false,

    /**
     * Select a cache to store gauges, counters, summaries and histograms between requests.
     * In a multi node setup you should ensure that each node writes to its own
     * cache instance or uses a node specific prefix.
     * Configure the cache store in config/cache.php.
     *
     * to use an in memory adapter for testing use array or null as your store
     * or remove the cache entry all together:
     *  'cache' => null       // InMemory implementation without laravel cache
     *  'cache' => 'array'    // InMemory implementation using laravel cache
     */
    'cache' => null,
];
