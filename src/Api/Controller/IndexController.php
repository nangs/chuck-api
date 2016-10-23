<?php
/**
 * IndexController.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Controller;

use \Chuck\App\Api\Exception\ConfigException as ConfigException;
use \Silex\Application as App;
use \Symfony\Component\HttpFoundation;
use \Symfony\Component\HttpKernel\Exception;
use \Symfony\Component\Routing\Generator\UrlGenerator as UrlGenerator;

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
     * @param  App                    $app
     * @param  HttpFoundation\Request $request
     * @throws ConfigException
     * @throws Exception\NotFoundHttpException
     */
    public function indexAction(App $app, HttpFoundation\Request $request)
    {
        if (! $app['config']['facebook_app_id']) {
            throw new ConfigException('FACEBOOK_APP_ID');
        }

        if (! $app['config']['facebook_app_page_id']) {
            throw new ConfigException('FACEBOOK_APP_PAGE_ID');
        }

        if (! $app['config']['facebook_jssdk_version']) {
            throw new ConfigException('FACEBOOK_JSSDK_VERSION');
        }

        if (! ($joke = $app['chuck.joke']->random()) instanceof \Chuck\Entity\Joke) {
            throw new Exception\NotFoundHttpException('Could not retrieve random joke.');
        }

        /* @var UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];

        return $app['twig']->render(
            'index.html',
            [
                'example_response_icon_url'  => 'https://assets.chucknorris.host/img/avatar/chuck-norris.png',
                'example_response_id'        => strval($joke->getId()),
                'example_response_text'      => strval($joke->getValue()),
                'example_response_url'       => $urlGenerator->generate(
                    'api.get_joke',
                    [ 'id' => $joke->getId() ],
                    UrlGenerator::ABSOLUTE_URL
                ),
                'slack_url'                  => Connect\SlackController::getAuthUrl($urlGenerator),
                'facebook_app_id'            => $app['config']['facebook_app_id'],
                'facebook_app_page_id'       => $app['config']['facebook_app_page_id'],
                'facebook_jssdk_version'     => $app['config']['facebook_jssdk_version']
            ]
        );
    }
}
