<?php
/**
 * JokeFormatter.php - created 2 Oct 2016 00:27:34
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Formatter;

use \Symfony\Component\Routing\Generator as Generator;

/**
 *
 * JokeFormatter
 *
 * @package Chuck\App\Api
 *
 */
class JokeFormatter
{

    /**
     *
     * @var Generator\UrlGenerator
     */
    private $urlGenerator;

    /**
     *
     * @param Generator\UrlGenerator $urlGenerator
     */
    public function __construct($urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     *
     * @param \Chuck\Entity\Joke $joke
     * @return array
     */
    public function format(\Chuck\Entity\Joke $joke)
    {
        return [
            'category' => $joke->getCategories(),
            'icon_url' => 'https://assets.chucknorris.host/img/avatar/chuck-norris.png',
            'id'       => $joke->getId(),
            'url'      => $this->urlGenerator->generate(
                'api.get_joke',
                [ 'id' => $joke->getId() ],
                Generator\UrlGenerator::ABSOLUTE_URL
            ),
            'value'    => $joke->getValue()
        ];
    }
}
