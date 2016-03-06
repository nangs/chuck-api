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
    public function indexAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);

        return $app['twig']->render('index.html', [
            'example_response_id'   => strval($this->jokeFacade->random()->getId()),
            'example_response_text' => strval($this->jokeFacade->random()->getValue())
        ]);
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
