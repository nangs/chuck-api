<?php

/**
 * ConfigException.php - created 23 Oct 2016 10:28:25
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\App\Api\Exception;

use \Symfony\Component\HttpKernel\Exception as Exception;

/**
 *
 * ConfigException
 *
 * @package Chuck\App\Api
 *
 */
class ConfigException extends Exception\HttpException
{
    /**
     *
     * @param string     $configId The configuration identifier
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($configId, \Exception $previous = null, $code = 0)
    {
        parent::__construct(
            500,
            sprintf('No value for "%s" specified.', $configId),
            $previous = null,
            [],
            $code
        );
    }
}