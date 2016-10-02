<?php

/**
 * FacebookController.php - created 2 Oct 2016 19:45:17
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Controller\Integration;

use \Symfony\Component\HttpKernel\Exception as Exception;
use \Symfony\Component\HttpFoundation as HttpFoundation;

/**
 *
 * FacebookController
 *
 * @package Chuck\App\Api
 *
 */
class FacebookController
{
    /**
     *
     * @param  \Silex\Application $app
     * @param  HttpFoundation\Request $request
     * @throws Exception\NotFoundHttpException
     * @return HttpFoundation\Response
     */
    public function indexAction(\Silex\Application $app, HttpFoundation\Request $request)
    {
        /* @var \Psr\Log\LoggerInterface $logger */
        $logger = $app['monolog'];

        $logger->info(
            'Incoming request data',
            $data = $request->request->all()
        );

        if ('page' !== $data['object']) {
            throw new Exception\NotFoundHttpException();
        }

        /* @var \Chuck\JokeFacade $jokeFacade */
        $jokeFacade = $app['chuck.joke'];

        /* @var \Chuck\JokeFacade $jokeFacade */
        $joke = $jokeFacade->random();

        $error = false;
        foreach($data['entry'] as $entry) {
            foreach($entry['messaging'] as $messaging) {
                $params = [
                    'recipient' => [
                        'id'   => $messaging['sender']['id']
                    ],
                    'message'   => [
                        'text' => $joke->getValue()
                    ]
                ];

                try {
                    /* @var \Facebook\FacebookResponse $facebook */
                    $facebookResponse = $app['facebook.graph-sdk']->post('/me/messages', $params);
                } catch (\Exception $exception) {
                    $error = true;
                    $logger->error($exception->getMessage(), [
                        'exception' => $exception->__toString()
                    ]);
                }
            }
        }

        return new HttpFoundation\Response(
            null,
            false === $error
                ? HttpFoundation\Response::HTTP_OK
                : HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
            [ 'content-type' => 'text/plain' ]
        );
    }

    /**
     *
     * @param  \Silex\Application                  $app
     * @param  HttpFoundation\Request              $request
     * @throws Exception\UnauthorizedHttpException
     * @return HttpFoundation\Response
     */
    public function subscribeAction(\Silex\Application $app, HttpFoundation\Request $request)
    {
        if ('subscribe' === $request->query->get('hub_mode') &&
            $app['config']['facebook_verification_token'] === $request->query->get('hub_verify_token') &&
            $challenge = $request->query->get('hub_challenge')
        ) {
            return new HttpFoundation\Response(
                $challenge,
                HttpFoundation\Response::HTTP_OK,
                [ 'content-type' => 'text/plain' ]
            );
        }

        throw new Exception\UnauthorizedHttpException();
    }
}
