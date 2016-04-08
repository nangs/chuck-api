<?php

/**
 * SlackController.php - created Mar 6, 2016 3:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller\Jokes;

/**
 *
 * SlackController
 *
 * @package Chuck\App\Api
 *
 */
class SlackController
{
    use \Chuck\Util\LoggerTrait;

    /**
     *
     * @var string
     */
    protected static $iconUrl = 'https://assets.chucknorris.host/img/avatar/chuck-norris.png';

    /**
     *
     * @param  string $text
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    protected function doLogging(
        $text,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $this->logInfo(
            json_encode([
                'type'      => 'slack_command',
                'reference' => $request->headers->get('HTTP_X_REQUEST_ID', \Chuck\Util::createSlugUuid()),
                'meta'      => [
                    'request'  => [
                        'token'        => $request->get('token'),
                        'team_id'      => $request->get('team_id'),
                        'team_domain'  => $request->get('team_domain'),
                        'channel_id'   => $request->get('channel_id'),
                        'channel_name' => $request->get('channel_name'),
                        'user_id'      => $request->get('user_id'),
                        'user_name'    => $request->get('user_name'),
                        'command'      => $request->get('command'),
                        'text'         => $request->get('text'),
                        'response_url' => $request->get('response_url')
                    ],
                    'response' => [
                        'text' => $text
                    ]
                ]
            ])
        );
    }

    /**
     *
     * @param  \Chuck\JokeFacade $jokeFacade
     * @param  string $showCount
     * @return string
     */
    protected function getCategoriesText(\Chuck\JokeFacade $jokeFacade, $showCount = false)
    {
        $response = null;
        foreach ($categories = $jokeFacade->getCategories() as $category) {
            $response[] = $showCount
                ? sprintf('%s (%d)', $category['name'], $category['count'])
                : $category['name'];
        }

        if (! $showCount) {
            asort($response);
        }

        return sprintf(
            'Available categories are: `%s`. Type `/chuck {category_name}` to retrieve a random joke from within the given category.',
            implode('`, `', $response)
        );
    }

    /**
     *
     * @param \Chuck\App\Api\Model\SlackInput           $input
     * @param \Chuck\JokeFacade                         $jokeFacade
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function doSearchCommand(
        \Chuck\App\Api\Model\SlackInput           $input,
        \Chuck\JokeFacade                         $jokeFacade,
        \Symfony\Component\HttpFoundation\Request $request
    ) {

        $offset = (int) ! empty($input->getArgStart())
            ? $input->getArgStart() - 1
            : 0;

        $attachments = [];
        $response    = $jokeFacade->searchByQuery(
            $query = $input->getInputWithoutArgs(),
            $limit  = 5,
            ! empty($offset) ? $offset : 0
        );

        if ($response['result']) {
            $showJokeId  = $input->hasIdArg();
            $showJokeCat = $input->hasCatArg();

            foreach ($response['result'] as $index => $joke) {
                $attachment = [
                    'title' => null,
                    'text'  => $joke->getValue() . "\n"
                ];

                if ($showJokeCat) {
                    $attachment['fields'][] = [
                        'title' => 'Category',
                        'value' => $joke->hasCategory()
                            ? implode(',', $joke->getCategories())
                            : '`false`',
                        'short' => false
                    ];
                }

                if ($showJokeId) {
                    $attachment['fields'][] = [
                        'title' => 'Id',
                        'value' => $joke->getId(),
                        'short' => false
                    ];
                }

                array_push($attachments, $attachment);
            }

            $text = sprintf(
                '*Search results: %s - %s of %s*.',
                0 === $offset ? 1 : $offset,
                $shown = $offset + $index + 1,
                number_format($response['total'], 0, '.', ','),
                $query
            );

            if ($shown < $response['total']) {
                $text .= sprintf(
                    ' Type `/chuck ? %s --start %s` to see more results.',
                    $query,
                    $offset + $index + 2
                );
            }

        } else {
            $text = sprintf(
                'Your search for *"%s"* did not match any joke. Make sure that all words are spelled correctly. Try different keywords. Try more general keywords.',
                $query
            );
        }

        $this->doLogging($text, $request);

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url'      => self::$iconUrl,
                'response_type' => 'in_channel',
                'attachments'   => $attachments ? $attachments: null,
                'text'          => $text,
                'mrkdwn'        => true,
                'mrkdwn_in'     => [
                    'text', 'pretext'
                ]
            ],
            200,
            [
                'Access-Control-Allow-Origin'      => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods'     => 'GET, HEAD',
                'Access-Control-Allow-Headers'     => 'Content-Type, Accept, X-Requested-With'
            ]
            );
    }

    /**
     *
     * @param  \Silex\Application                        $app
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(
        \Silex\Application $app,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $input = \Chuck\App\Api\Model\SlackInput::fromString($request->get('text'));
        $this->setLogger($app['monolog']);

        if ($input->isEditMode()) {
            $category = $input->getArgCategory();
            $joke     = $input->getArgJoke();
            $id       = $input->getArgId();

            $this->doLogging(
                $text = 'This lazy programmer still hasn\'t implemented this feature ...',
                $request
            );

            return new \Symfony\Component\HttpFoundation\JsonResponse(
                [
                    'icon_url' => self::$iconUrl,
                    'text'     => $text
                ],
                200,
                [
                    'Access-Control-Allow-Origin'      => '*',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Methods'     => 'GET, HEAD',
                    'Access-Control-Allow-Headers'     => 'Content-Type, Accept, X-Requested-With'
                ]
            );
        }

        if ($input->isShowCategories()) {

            $text = $input->hasCountArg()
                ? $this->getCategoriesText($app['chuck.joke'], true)
                : $this->getCategoriesText($app['chuck.joke']);

            $this->doLogging($text, $request);

            return new \Symfony\Component\HttpFoundation\JsonResponse(
                [
                    'icon_url'      => self::$iconUrl,
                    'text'          => $text,
                    'mrkdwn'        => true
                ],
                200,
                [
                    'Access-Control-Allow-Origin'      => '*',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Methods'     => 'GET, HEAD',
                    'Access-Control-Allow-Headers'     => 'Content-Type, Accept, X-Requested-With'
                ]
            );
        }

        if ($input->isSearchMode()) {
            return $this->doSearchCommand($input, $app['chuck.joke'], $request);
        }

        $joke = $app['chuck.joke']->random(
            $category = $input->getInputWithoutArgs()
        );

        if ($category && ! $joke->getValue()) {
            $text = sprintf(
                'Sorry dude, we\'ve found no jokes for the given category ("%s"). Type `/chuck -cat` to see available categories or search by query `/chuck ? {search_term}`.',
                $category
            );
        } else {
             $text = $input->hasIdArg()
                ? sprintf('%s `[ joke_id: %s ]`', $joke->getValue(), $joke->getId())
                : $joke->getValue();
        }

        $this->doLogging($text, $request);

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'icon_url'      => self::$iconUrl,
                'response_type' => 'in_channel',
                'text'          => $text,
                'mrkdwn'        => true
            ],
            200,
            [
                'Access-Control-Allow-Origin'      => '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods'     => 'GET, HEAD',
                'Access-Control-Allow-Headers'     => 'Content-Type, Accept, X-Requested-With'
            ]
        );
    }
}
