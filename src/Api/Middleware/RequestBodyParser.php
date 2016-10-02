<?php

/**
 * RequestBodyParser.php - created 2 Oct 2016 10:55:09
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Middleware;

use \Symfony\Component\HttpFoundation as HttpFoundation;

/**
 *
 * RequestBodyParser
 *
 * @package Chuck\App\Api
 *
 */
class RequestBodyParser
{

    /**
     *
     * @param  HttpFoundation\Request $request
     * @param  \Silex\Application $app
     */
    public static function parse(HttpFoundation\Request $request, \Silex\Application $app)
    {
        if (0 === strpos($request->headers->get('content-type'), 'application/json')) {
            $data = json_decode(
                $request->getContent(),
                true
            );

            $request->request->replace(
                is_array($data) ? $data : []
            );
        }
    }
}
