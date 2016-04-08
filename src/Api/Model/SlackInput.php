<?php

/**
 * SlackInput.php - created Apr 8, 2016 7:07:23 PM
 *
 * @copyright Copyright (c) pinkbigmacmedia
 *
 */
namespace Chuck\App\Api\Model;

/**
 *
 * SlackController
 *
 * @package Chuck\App\Api
 *
 */
class SlackInput
{

    /**
     *
     * @var string
     */
    protected $input;

    // Edit constants
    const ARG_CAT   = 'cat';
    const ARG_COUNT = 'count';
    const ARG_ID    = 'id';
    const ARG_JOKE  = 'joke';
    const ARG_START = 'start';

    // Mode constants
    const MODE_EDIT     = 'edit';
    const MODE_SEARCH   = '?';
    const MODE_SHOW_CAT = 'cat';

    /**
     *
     * @param  array $values
     * @return void
     */
    public function __construct($values)
    {
        $this->setInput(\Chuck\Util::getOrNull($values['input']));
    }

    /**
     *
     * @param  string $string
     * @return \Chuck\App\Api\Model\SlackInput
     */
    public static function fromString($string)
    {
        return new static([
            'input' => $string
        ]);
    }

    /**
     *
     * @param  string $argument
     * @return string
     */
    protected function getArg($argument)
    {
        preg_match("#--$argument\s+\K\w+#", $this->input, $match);

        return $match[0];
    }

    /**
     *
     * @return string
     */
    public function getArgCategory()
    {
        return $this->getArg(self::ARG_CAT);
    }

    /**
     *
     * @return string
     */
    public function getArgId()
    {
        return $this->getArg(self::ARG_ID);
    }

    /**
     *
     * @return string
     */
    public function getArgJoke()
    {
        return $this->getArg(self::ARG_JOKE);
    }

    /**
     *
     * @return string
     */
    public function getArgStart()
    {
        return $this->getArg(self::ARG_START);
    }

    /**
     *
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     *
     * @return string
     */
    public function getInputWithoutArgs()
    {
        preg_match_all('~-?-\w+\s*\w+~', $response = $this->input, $matches);

        foreach ($matches as $match) {
            $response = str_replace($match, '', $response);
        }

        $response = preg_replace('~\\?~', '', $response, $limit = 1);

        return trim($response);
    }

    /**
     *
     * @return boolean
     */
    public function hasCatArg()
    {
        return strpos($this->input, '--' . self::ARG_CAT) !== false;
    }

    /**
     *
     * @return boolean
     */
    public function hasCountArg()
    {
        return strpos($this->input, '--' . self::ARG_COUNT) !== false;
    }

    /**
     *
     * @return boolean
     */
    public function hasIdArg()
    {
        return strpos($this->input, '--' . self::ARG_ID) !== false;
    }

    /**
     *
     * @return boolean
     */
    public function isEditMode()
    {
        return strpos($this->input, '-' . self::MODE_EDIT) !== false;
    }

    /**
     *
     * @return boolean
     */
    public function isSearchMode()
    {
        return strpos($this->input, self::MODE_SEARCH) !== false;
    }

    /**
     *
     * @return boolean
     */
    public function isShowCategories()
    {
        return strpos($this->input, '-' . self::MODE_SHOW_CAT) !== false;
    }

    /**
     *
     * @param  mixed $value
     * @return \Chuck\App\Api\Model\SlackInput
     */
    public function setInput($value)
    {
        if ($value) {
            $this->input = strval($value);
        }
        return $this;
    }
}
