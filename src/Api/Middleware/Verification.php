<?php

/**
 * Verification.php - created 2 Oct 2016 10:55:09
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Middleware;

use Chuck\App\Api\Exception as Exception;
use \Symfony\Component\HttpFoundation as HttpFoundation;

/**
 *
 * Verification
 *
 * @package Chuck\App\Api
 *
 */
class Verification
{
    /**
     * Verify the slack origin
     *
     * @param  HttpFoundation\Request $request
     * @param  \Silex\Application $app
     * @throws Exception\SlackVerificationTokenException
     */
    public static function slackOrigin(HttpFoundation\Request $request, \Silex\Application $app)
    {
        $token = $request->request->get(
            'token',
            $request->query->get('token', null)
        );

        if ($token !== $app['config']['slack_verification_token']) {
            throw new Exception\SlackVerificationTokenException();
        }
    }
}
