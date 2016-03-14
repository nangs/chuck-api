<?php

/**
 * ServicesLoader.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api;

/**
 *
 * ServicesLoader
 *
 * @package \Chuck\App\Api
 *
 */
class ServicesLoader implements \Silex\ServiceProviderInterface
{
    /**
     *
     * @var \Silex\Application
     */
    protected $app;

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * {@inheritDoc}
     * @see \Silex\ServiceProviderInterface::boot()
     */
    public function boot(\Silex\Application $app)
    {
    }

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * {@inheritDoc}
     * @see \Silex\ServiceProviderInterface::register()
     */
    public function register(\Silex\Application $app)
    {
        $this->app = $app;

        $this->app['chuck.joke'] = $this->app->share(
            function () {
                return new \Chuck\JokeFacade(
                    new \Chuck\Broker\Joke(new \Chuck\Database('fromEnv'), new \Webmozart\Json\JsonDecoder())
                );
            }
        );
    }
}
