<?php

/**
 * JokesController.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller;

/**
 *
 * JokesController.php
 *
 * @package Chuck\App\Api
 *
 */
class JokesController
{

    /**
     *
     * @var \Chuck\JokeFacade
     */
    protected $jokeFacade;

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url' => 'https://api.chucknorris.io/img/avatar/chuck-norris.png',
                'text'     => $this->jokeFacade->random()->getValue()
            ],
            200,
            [
                'Access-Control-Allow-Origin'      => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods'     => 'GET, HEAD',
                'Access-Control-Allow-Headers'     => 'Content-Type, Accept, X-Requested-With'
            ]
        );
    }

    /**
     *
     * @param \Chuck\JokeFacade $jokeFacade
     * @return void
     */
    protected function setJokeFacade(\Chuck\JokeFacade $jokeFacade)
    {
        $this->jokeFacade = $jokeFacade;
    }

    /**
     *
     * @param \Silex\Application $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function slackAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url'      => 'https://api.chucknorris.io/img/avatar/chuck-norris-alt-01.png',
                'response_type' => 'in_channel',
                'text'          => $this->jokeFacade->random()->getValue()
            ]
        );
    }
}
