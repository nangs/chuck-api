<?php

/**
 * App.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

$app = new \Silex\Application();

$app->extend('routes', function (
    \Symfony\Component\Routing\RouteCollection $routes,
    \Silex\Application                         $app
) {
    $loader = new \Symfony\Component\Routing\Loader\YamlFileLoader(
        new \Symfony\Component\Config\FileLocator(__DIR__ . '/../config')
    );

    $collection = $loader->load('routes.yml');
    $routes->addCollection($collection);

    return $routes;
});

$app->register(new \Chuck\App\Api\ServicesLoader());

$app->error(function (\Exception $exception, $httpStatusCode) use ($app) {
    if ($app['debug']) {
        return;
    }
});

return $app;
