<?php

/**
 * SlackController.php - created Mar 30, 2016 11:03:18 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Controller\Connect;

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
     * @var \stdClass
     */
    protected static $config;

    /**
     *
     * @var \Bramdevries\Oauth\Client\Provider\Slack
     */
    protected static $provider;

    /**
     *
     * @param  \Silex\Application                        $app
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function connectAction(
        \Silex\Application $app,
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        $this->setLogger($app['monolog']);
        self::$config = json_decode(getenv('SLACK_AUTH'));

        if ($code = $request->get('code', null)) {
            $provider = self::getAuthProvider($app['url_generator']);

            $response = $provider->getResourceOwner(
                $token = $provider->getAccessToken('authorization_code', [ 'code' => $code ])
            );

            $resourceOwner = $response instanceof \Bramdevries\Oauth\Client\Provider\ResourceOwner
                ? $response->toArray()
                : [];

            $this->logInfo(
                json_encode([
                    'type'      => 'slack_connect',
                    'reference' => $request->headers->get('HTTP_X_REQUEST_ID', \Chuck\Util::createSlugUuid()),
                    'meta'      => [
                        'resource_owner'  => [
                            'team_domain' => $resourceOwner['team'],
                            'team_id'     => $resourceOwner['team_id'],
                            'user_id'     => $resourceOwner['user_id'],
                            'user_name'   => $resourceOwner['user']
                        ]
                    ]
                ])
            );

            return $app['twig']->render(
                'message.html',
                [
                    'page_title' => 'The app was successfully installed for your Slack team.',
                    'message'    => [
                        'type'  => 'success',
                        'value' => 'Congrats! The app was successfully installed for your Slack team and you\'re ready'
                        . ' to laugh. Start by typing \'\chuck\' into your Slack console.' ]
                ]
            );
        }

        return $app['twig']->render(
            'message.html',
            [
                'page_title' => $msg = 'An error has occurred.',
                'message'    => [
                    'type'  => 'error',
                    'value' => $msg
                ]
            ]
        );
    }

    /**
     * Get auth provider
     *
     * @return \Bramdevries\Oauth\Client\Provider\Slack
     */
    private static function getAuthProvider(\Symfony\Component\Routing\Generator\UrlGenerator $urlGenerator)
    {
        if (null != self::$provider) {
            return self::$provider;
        }

        return self::$provider = new \Bramdevries\Oauth\Client\Provider\Slack([
            'clientId'     => self::getConfig()->clientId,
            'clientSecret' => self::getConfig()->clientSecret,
            'redirectUri'  => $urlGenerator->generate(
                'api.connect_slack',
                [],
                \Symfony\Component\Routing\Generator\UrlGenerator::ABSOLUTE_URL
            )
        ]);
    }

    /**
     * Get slack authorize url
     *
     * @param \Symfony\Component\Routing\Generator\UrlGenerator $urlGenerator
     * @return string
     */
    public static function getAuthUrl(\Symfony\Component\Routing\Generator\UrlGenerator $urlGenerator)
    {
        $data = [
            'client_id'    => self::getConfig()->clientId,
            'redirect_uri' => $urlGenerator->generate(
                'api.connect_slack',
                [],
                \Symfony\Component\Routing\Generator\UrlGenerator::ABSOLUTE_URL
            ),
            'scope'     => 'commands'
        ];

        return sprintf('https://slack.com/oauth/authorize?%s', http_build_query($data));
    }

    /**
     * Get the slack auth config
     *
     * object()
     *   public 'clientId'     => string
     *   public 'clientSecret' => string
     *
     * @throws \Exception
     * @return \stdClass
     */
    private static function getConfig()
    {
        if (null != self::$config) {
            return self::$config;
        }

        if (false === $slackAuth = getenv('SLACK_AUTH')) {
            throw new \Exception('Missing environment variable "SLACK_AUTH".');
        }

        return self::$config = json_decode($slackAuth);
    }
}
