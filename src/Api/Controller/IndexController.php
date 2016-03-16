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

        $joke = $this->jokeFacade->random();

        return $app['twig']->render('index.html',  [
            'example_response_icon_url' => 'https://api.chucknorris.io/img/avatar/chuck-norris.png',
            'example_response_id'       => strval($joke->getId()),
            'example_response_text'     => strval($joke->getValue()),
            'example_response_url'      => $app['url_generator']->generate('api.get_joke', ['id' => $joke->getId()])
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
