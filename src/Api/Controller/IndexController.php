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
     * @var \Chuck\JokeFacade
     */
    protected $jokeFacade;

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function slackAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url' => 'http://api.chucknorris.io/img/avatar/chuck-norris.png',
                'text'     => $this->jokeFacade->random()->getValue()
            ]
        );
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);

        return new \Symfony\Component\HttpFoundation\Response($this->jokeFacade->random()->getValue());
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
}
