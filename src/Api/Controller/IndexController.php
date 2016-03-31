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
 * IndexController
 *
 * @package Chuck\App\Api
 *
 */
class IndexController
{
    /**
     *
     * @param  \Silex\Application                        $app
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(
        \Silex\Application $app,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $joke = $app['chuck.joke']->random();

        return $app['twig']->render(
            'index.html',
            [
                'example_response_icon_url' => 'https://api.chucknorris.io/img/avatar/chuck-norris.png',
                'example_response_id'       => strval($joke->getId()),
                'example_response_text'     => strval($joke->getValue()),
                'example_response_url'      => $app['url_generator']->generate(
                    'api.get_joke',
                    [
                        'id' => $joke->getId()
                    ]
                ),
                'slack_url'                 => \Chuck\App\Api\Controller\Connect\SlackController::getAuthUrl(
                    $app['url_generator']
                )
            ]
        );
    }
}
