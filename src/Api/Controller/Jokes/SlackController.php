<?php

/**
 * SlackController.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller\Jokes;

/**
 *
 * SlackController
 *
 * @package Chuck\App\Api
 *
 */
class SlackController
{
    use \Chuck\Util\LoggerTrait;

    /**
     *
     * @param  \Chuck\Entity\Joke                        $joke
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    protected function doLogging(
        \Chuck\Entity\Joke                        $joke,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $this->logInfo(
            json_encode([
                'type'      => 'slack_command',
                'reference' => $request->headers->get('HTTP_X_REQUEST_ID', \Chuck\Util::createSlugUuid()),
                'meta'      => [
                    'request'  => [
                        'token'        => $request->get('token'),
                        'team_id'      => $request->get('team_id'),
                        'team_domain'  => $request->get('team_domain'),
                        'channel_id'   => $request->get('channel_id'),
                        'channel_name' => $request->get('channel_name'),
                        'user_id'      => $request->get('user_id'),
                        'user_name'    => $request->get('user_name'),
                        'command'      => $request->get('command'),
                        'text'         => $request->get('text'),
                        'response_url' => $request->get('response_url')
                    ],
                    'response' => [
                        'joke_id' => $joke->getId()
                    ]
                ]
            ])
        );
    }

    /**
     *
     * @param  \Silex\Application                        $app
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(
        \Silex\Application $app,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $this->setLogger($app['monolog']);
        $this->doLogging($joke = $app['chuck.joke']->random(), $request);

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
