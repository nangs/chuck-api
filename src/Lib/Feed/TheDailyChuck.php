<?php

/**
 * TheDailyChuck.php - created Fri 29 Dec 13:50:13 GMT 2017
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Chuck\Feed;

use Aws\S3\S3Client;
use Chuck\JokeFacade;
use DateTime;

/**
 * TheDailyChuck
 *
 * @package Chuck\Lib
 */
class TheDailyChuck
{

    /** @var string */
    protected $bucket;

    /** @var JokeFacade */
    protected $jokeFacade;

    /** @var string */
    protected $key;

    /** @var S3Client */
    protected $s3Client;

    /**
     *
     * @param array $config
     * @param S3Client $s3Client
     * @param JokeFacade $jokeFacade
     */
    public function __construct(array $config, S3Client $s3Client, JokeFacade $jokeFacade)
    {
        $this->bucket = $config['bucket'];
        $this->jokeFacade = $jokeFacade;
        $this->key = $config['key'];
        $this->s3Client = $s3Client;
    }

    /**
     *
     * @return array
     */
    private function fetchIssueList()
    {
        if (! $this->s3Client->doesObjectExist($this->bucket, $this->key)) {
            return [];
        }

        $result = $this->s3Client->getObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $this->key
            ]);

        return json_decode($result['Body'], true);
    }

    /**
     *
     * @return array
     */
    public function getIssues()
    {
        $issueCount = 0;
        $issueList = $this->fetchIssueList();
        $now = new DateTime();
        $response = [];

        $published = function ($key) {
            return (new \DateTime($key))->format('r');
        };

        foreach ($issueList as $key => $issueElement) {
            $issueDate = new DateTime($key);

            if ($issueDate <= $now) {
                $joke = $this->jokeFacade->get($issueElement['joke_id']);

                array_push($response,
                    [
                        'issue' => ++ $issueCount,
                        'published' => $published($key),
                        'joke' => $joke
                    ]);
            }
        }

        $key = $now->format('Y-m-d');
        if (array_key_exists($key, $issueList)) {
            return array_reverse($response);
        }

        $retries = 33;
        $doesExist = function ($jokeId) use ($issueList) {
            foreach ($issueList as $issue) {
                if ($jokeId === $issue['joke_id']) {
                    return true;
                }
            }
            return false;
        };

        do {
            $joke = $this->jokeFacade->random(null, true);
            $retries -= 1;
        } while ($doesExist($joke->getId()) || $retries > 0);

        $issueList[$key] = [
            'joke_id' => $joke->getId()
        ];
        $this->updateIssueList($issueList);

        array_push($response,
            [
                'issue' => ++ $issueCount,
                'published' => $published($key),
                'joke' => $joke
            ]);

        return array_reverse($response);
    }

    /**
     *
     * @param array $issueList
     * @return \Aws\ResultInterface
     */
    private function updateIssueList($issueList = [])
    {
        return $this->s3Client->upload($this->bucket, $this->key, json_encode($issueList));
    }
}
