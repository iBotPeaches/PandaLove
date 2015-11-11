<?php namespace Onyx\Halo5\Helpers\Date;

class DateIntervalFractions extends \DateInterval
{
    /**
     * @var float
     */
    public $milliseconds;

    /**
     * DateIntervalFractions constructor.
     *
     * This class exists because of a bug in PHP. PHP cannot handle a valid ISO 8601 duration.
     * The milliseconds delimited by comma/period are ignored and cause the \DateInterval to crash.
     *
     * Extracting these milliseconds out patches DateInterval to work.
     *
     * @url https://bugs.php.net/bug.php?id=53831
     * @param string $interval_spec
     */
    public function __construct($interval_spec)
    {
        $this->milliseconds = 0;
        $matches = array();
        preg_match_all("#([0-9]*[.,]?[0-9]*)[S]#", $interval_spec, $matches);

        foreach ($matches[0] as $result)
        {
            $original = $result;

            list($seconds, $milliseconds) = explode(".", substr($result, 0, -1));

            $this->milliseconds = $milliseconds / pow(10, strlen($milliseconds) - 3);
            $interval_spec = str_replace($original, $seconds . "S", $interval_spec);
        }

        parent::__construct($interval_spec);
    }
}
