<?php

/**
 * PrivacyController.php - created 20 Oct 2016 22:17:52
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Controller;

use \Silex\Application as App;
use \Symfony\Component\HttpFoundation;

/**
 *
 * PrivacyController
 *
 * @package Chuck\App\Api
 *
 */
class PrivacyController
{
    /**
     *
     * @param  App                    $app
     * @param  HttpFoundation\Request $request
     * @return HttpFoundation\Response
     */
    public function indexAction(App $app, HttpFoundation\Request $request)
    {
        return $app['twig']->render('privacy.html');
    }
}
