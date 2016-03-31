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
 * JokesController
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
     * @var \Monolog\Logger
     */
    protected $logger;

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
        $this->setRequest($app['request']);

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
     * Set the logger
     *
     * @param \Monolog\Logger $logger
     * @return void
     */
    protected function setLogger(\Monolog\Logger $logger)
    {
        $this->logger = $logger;
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
        $this->setLogger($app['monolog']);
        $this->setRequest($app['request']);

        $joke = $this->jokeFacade->random();
        $this->logger->addInfo(
            json_encode([
                'type'      => 'slack_command',
                'reference' => $this->request->headers->get('HTTP_X_REQUEST_ID', \Chuck\Util::createSlugUuid()),
                'meta'      => [
                    'request'  => [
                        'token'        => $this->request->get('token'),
                        'team_id'      => $this->request->get('team_id'),
                        'team_domain'  => $this->request->get('team_domain'),
                        'channel_id'   => $this->request->get('channel_id'),
                        'channel_name' => $this->request->get('channel_name'),
                        'user_id'      => $this->request->get('user_id'),
                        'user_name'    => $this->request->get('user_name'),
                        'command'      => $this->request->get('command'),
                        'text'         => $this->request->get('text'),
                        'response_url' => $this->request->get('response_url')
                    ],
                    'response' => [
                        'joke_id' => $joke->getId()
                    ]
                ]
            ])
        );

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url'      => 'https://api.chucknorris.io/img/avatar/chuck-norris.png',
                'response_type' => 'in_channel',
                'text'          => $joke->getValue()
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
