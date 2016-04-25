<?php

/**
 * SitemapController.php - created Apr 25, 2016 9:36:01 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller;

/**
 *
 * SitemapController
 *
 * @package Chuck\App\Api
 *
 */
class SitemapController
{

    /**
     *
     * @var string
     */
    const ITEMS_PER_PAGE = 200;

    /**
     *
     * @param  \Silex\Application                        $app
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(\Silex\Application $app, \Symfony\Component\HttpFoundation\Request $request)
    {
        $sitemap = new \DOMDocument('1.0', 'UTF-8');
        $sitemap->formatOutput = true;

        $rootNode = $sitemap->createElement('sitemapindex');
        $sitemap->appendChild($rootNode);

        $rootNode->appendChild($xmlNamespace = $sitemap->createAttribute('xmlns'));
        $xmlNamespace->appendChild(
            $sitemap->createTextNode('http://www.google.com/schemas/sitemap/0.9')
        );

        $count = ceil($app['chuck.joke']->count() / self::ITEMS_PER_PAGE);

        while ($count) {
            $rootNode->appendChild(
                $child = $sitemap->createElement('sitemap')
            );

            $child->appendChild($locNode = $sitemap->createElement('loc'));
            $locNode->appendChild(
                $sitemap->createTextNode($app['url_generator']->generate(
                    'sitemap.jokes',
                    [ 'page' => $count ],
                    \Symfony\Component\Routing\Generator\UrlGenerator::ABSOLUTE_URL
                ))
            );

            $child->appendChild($lastModNode = $sitemap->createElement('lastmod'));
            $lastModNode->appendChild(
                $sitemap->createTextNode(date('Y-m-d'))
            );

            $count--;
        }

        return new \Symfony\Component\HttpFoundation\Response(
            $sitemap->saveXML(),
            200,
            [ 'content-type' => 'application/xml' ]
        );
    }

    /**
     *
     * @param  \Silex\Application                         $app
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  integer                                    $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jokesAction(
        \Silex\Application                        $app,
        \Symfony\Component\HttpFoundation\Request $request,
        $page = null
    ) {
        $sitemap = new \DOMDocument('1.0', 'UTF-8');
        $sitemap->formatOutput = true;

        $rootNode = $sitemap->createElement('urlset');
        $sitemap->appendChild($rootNode);

        $rootNode->appendChild($xmlNamespace = $sitemap->createAttribute('xmlns'));
        $xmlNamespace->appendChild(
            $sitemap->createTextNode('http://www.google.com/schemas/sitemap/0.9')
        );

        $jokes = $app['chuck.joke']->iterate(
            self::ITEMS_PER_PAGE,
            ($page * self::ITEMS_PER_PAGE) - self::ITEMS_PER_PAGE
        );

        foreach ($jokes as $joke) {
            $child = $sitemap->createElement('url');
            $rootNode->appendChild($child);

            $child->appendChild($locNode = $sitemap->createElement('loc'));
            $locNode->appendChild(
                $sitemap->createTextNode($app['url_generator']->generate(
                    'api.get_joke',
                    [ 'id' => $joke->getId()     ],
                    \Symfony\Component\Routing\Generator\UrlGenerator::ABSOLUTE_URL
                ))
            );
        }

        return new \Symfony\Component\HttpFoundation\Response(
            $sitemap->saveXML(),
            200,
            [ 'content-type' => 'application/xml' ]
        );
    }
}
