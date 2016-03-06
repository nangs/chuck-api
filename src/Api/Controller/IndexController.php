<?php

/**
 * IndexController.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller;

/**
 *
 * IndexController.php
 *
 * @package Chuck\App\Api
 *
 */
class IndexController
{
    /**
     *
     * @param  \Silex\Application $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(\Silex\Application $app)
    {
        return new \Symfony\Component\HttpFoundation\Response($app['chuck.joke']->random()->getValue());
    }
}
