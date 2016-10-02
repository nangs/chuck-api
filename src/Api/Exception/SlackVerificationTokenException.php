<?php

/**
 * SlackVerificationTokenException.php - created 2 Oct 2016 13:19:43
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
 * SlackVerificationTokenException
 *
 * @package Chuck\App\Api
 *
 */
class SlackVerificationTokenException extends Exception\PreconditionFailedHttpException
{
    /**
     *
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct(\Exception $previous = null, $code = 0)
    {
        parent::__construct(
            'Could not verify "SLACK_VERIFICATION_TOKEN".',
            $previous = null,
            $code
        );
    }
}
