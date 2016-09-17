<?php

/**
 * JsonResponse.php - created 17 Sep 2016 22:14:09
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Model;

/**
 *
 * JsonResponse.php
 *
 * @package Chuck\App\Api
 *
 */
class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{

    /**
     *
     * @var array
     */
    private static $defaultHeaders = [
        'Access-Control-Allow-Origin'      => '*',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Methods'     => 'GET, HEAD',
        'Access-Control-Allow-Headers'     => 'Content-Type, Accept, X-Requested-With'
    ];

    /**
     *
     * @param mixed $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     */
    public function __construct($data = null, $status = 200, $headers = [])
    {
        parent::__construct($data, $status, array_merge(self::$defaultHeaders, $headers));
    }
}
