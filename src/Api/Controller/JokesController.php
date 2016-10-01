<?php
/**
 * JokesController.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Controller;

use \Chuck\App\Api\Model as Model;
use \Chuck\Entity as Entity;
use \Symfony\Component\HttpFoundation as HttpFoundation;
use \Symfony\Component\HttpKernel\Exception as Exception;
use \Symfony\Component\Routing\Generator as Generator;

/**
 *
 * JokesController
 *
 * @package Chuck\App\Api
 *
 */
class JokesController
{
    use \Chuck\Util\LoggerTrait;

    /**
     *
     * @param  \Silex\Application $app
     * @param  HttpFoundation\Request $request
     * @throws Exception\NotFoundHttpException
     * @return HttpFoundation\Response|Model\JsonResponse
     */
    public function categoryAction(\Silex\Application $app, HttpFoundation\Request $request)
    {
        /* @var \Chuck\JokeFacade $jokeFacade */
        $jokeFacade =  $app['chuck.joke'];

        /* @var array $categories */
        $categories = array_map(function($category) {
            return $category['name'];
        }, $jokeFacade->getCategories());

        if ('text/plain' === $request->headers->get('accept')) {
            return new HttpFoundation\Response(
                implode("\n", $categories),
                HttpFoundation\Response::HTTP_OK,
                [ 'content-type' => 'text/plain' ]
            );
        }

        return new Model\JsonResponse($categories);
    }

    /**
     *
     * @param  \Silex\Application     $app
     * @param  HttpFoundation\Request $request
     * @param  string                 $id
     * @throws Exception\NotFoundHttpException
     * @return string
     */
    public function getAction(\Silex\Application $app, HttpFoundation\Request $request, $id)
    {
        $jokeWindow = $app['chuck.joke']->window($id);

        if (! $jokeWindow instanceof Entity\JokeWindow) {
            throw new Exception\NotFoundHttpException();
        }

        /* @var Generator\UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];

        return $app['twig']->render('joke.html', [
            'page_title'    => trim(substr($jokeWindow->getCurrent()->getValue(), 0, 120), '.') . ' ...',
            'current_joke'  => [
                'id'    => $jokeWindow->getCurrent()->getId(),
                'title' => trim(substr($jokeWindow->getCurrent()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $urlGenerator->generate('api.get_joke', ['id' => $id]),
                'value' => $jokeWindow->getCurrent()->getValue()
            ],
            'next_joke'     => [
                'id'    => $next = $jokeWindow->getNext()->getId(),
                'title' => trim(substr($jokeWindow->getNext()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $urlGenerator->generate('api.get_joke', ['id' => $next]),
                'value' => $jokeWindow->getNext()->getValue()
            ],
            'previous_joke' => [
                'id'    => $prev = $jokeWindow->getPrevious()->getId(),
                'title' => trim(substr($jokeWindow->getPrevious()->getValue(), 0, 120), '.') . ' ...',
                'url'   => $urlGenerator->generate('api.get_joke', ['id' => $prev]),
                'value' => $jokeWindow->getPrevious()->getValue()
            ]
        ]);
    }

    /**
     *
     * @param  \Silex\Application $app
     * @param  HttpFoundation\Request $request
     * @throws Exception\NotFoundHttpException
     * @return HttpFoundation\Response|Model\JsonResponse
     */
    public function randomAction(\Silex\Application $app, HttpFoundation\Request $request)
    {
        $joke = $app['chuck.joke']->random(
            $category = $request->query->get('category', null)
        );

        if (! $joke instanceof Entity\Joke) {
            throw new Exception\NotFoundHttpException();
        }

        /* @var Generator\UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];

        if ('text/plain' === $request->headers->get('accept')) {
            return new HttpFoundation\Response(
                $joke->getValue(),
                HttpFoundation\Response::HTTP_OK,
                [ 'content-type' => 'text/plain' ]
            );
        }

        return new Model\JsonResponse(
            [
                'category' => $joke->getCategories(),
                'icon_url' => 'https://assets.chucknorris.host/img/avatar/chuck-norris.png',
                'id'       => $joke->getId(),
                'url'      => $urlGenerator->generate(
                    'api.get_joke',
                    [ 'id' => $joke->getId() ],
                    Generator\UrlGenerator::ABSOLUTE_URL
                ),
                'value'    => $joke->getValue()
            ]
        );
    }
}
