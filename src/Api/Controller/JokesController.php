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
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     *
     * @param  \Silex\Application $app
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);
        $this->setRequest($app['request']);

        $jokeWindow = $this->jokeFacade->window($this->request->get('id'));

        return $app['twig']->render('joke.html', [
            'page_title'    => trim(substr($jokeWindow->getCurrent()->getValue(), 0, 120), '.') . ' ...',
            'current_joke'  => [
                'id'    => $id = $jokeWindow->getCurrent()->getId(),
                'title' => trim(substr($jokeWindow->getCurrent()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $app['url_generator']->generate('api.get_joke', ['id' => $id]),
                'value' => $jokeWindow->getCurrent()->getValue()
            ],
            'next_joke'     => [
                'id'    => $next = $jokeWindow->getNext()->getId(),
                'title' => trim(substr($jokeWindow->getNext()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $app['url_generator']->generate('api.get_joke', ['id' => $next]),
                'value' => $jokeWindow->getNext()->getValue()
            ],
            'previous_joke' => [
                'id'    => $prev = $jokeWindow->getPrevious()->getId(),
                'title' => trim(substr($jokeWindow->getPrevious()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $app['url_generator']->generate('api.get_joke', ['id' => $prev]),
                'value' => $jokeWindow->getPrevious()->getValue()
            ]
        ]);
    }

    /**
     *
     * @param  \Silex\Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function randomAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url' => 'https://api.chucknorris.io/img/avatar/chuck-norris.png',
                'id'       => $this->jokeFacade->random()->getId(),
                'value'    => $this->jokeFacade->random()->getValue()
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    protected function setRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;
    }

    /**
     *
     * @param  \Silex\Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function slackAction(\Silex\Application $app)
    {
        $this->setJokeFacade($app['chuck.joke']);

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url' => 'https://api.chucknorris.io/img/avatar/chuck-norris.png',
                'response_type' => 'in_channel',
                'text'          => $this->jokeFacade->random()->getValue()
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
}
