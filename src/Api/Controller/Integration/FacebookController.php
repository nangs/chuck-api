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
     * @param  \Silex\Application      $app
     * @param  HttpFoundation\Request  $request
     * @return HttpFoundation\Response
     */
    public function indexAction(\Silex\Application $app, HttpFoundation\Request $request)
    {
        /* @var \Psr\Log\LoggerInterface $logger */
        $logger = $app['monolog'];

        $logger->info(
            $data = json_encode($request->request->all())
        );

        return new HttpFoundation\Response(
            $data,
            HttpFoundation\Response::HTTP_OK,
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
