<?php

/**
 * App.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

$app = new \Silex\Application();

$app['application_env'] = \Chuck\Util::getEnvOrDefault('APPLICATION_ENV', 'production');
$app['debug']           = 'production' === $app['application_env'] ? false : true;

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
$app->register(
    new \Silex\Provider\MonologServiceProvider(),
    [
        'monolog.name'    => 'chuck_norris',
        'monolog.handler' => $streamHandler = new \Monolog\Handler\StreamHandler(
            'php://stdout',
            \Monolog\Logger::INFO
        )
    ]
);
$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path'    => __DIR__ . '/../assets/views/'
]);
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
        return sprintf('https://assets.chucknorris.host/%s', ltrim($asset, '/'));
    }));
    return $twig;
}));

$app->error(function (\Exception $exception, $httpStatusCode) use ($app) {
    if ($app['debug']) {
        return;
    }
});

return $app;
