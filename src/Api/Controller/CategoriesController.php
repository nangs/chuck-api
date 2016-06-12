<?php

/**
 * CategoriesController.php - created Apr 17, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller;

/**
 *
 * CategoriesController
 *
 * @package Chuck\App\Api
 *
 */
class CategoriesController
{
    use \Chuck\Util\LoggerTrait;

    /**
     *
     * @var \Chuck\JokeFacade
     */
    protected $jokeFacade;

    /**
     *
     * @param  \Silex\Application $app
     * @param  string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(
        \Silex\Application $app,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $this->jokeFacade = $app['chuck.joke'];

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            $this->jokeFacade->getCategories()
        );
    }
}
