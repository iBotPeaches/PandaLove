<?php namespace Onyx\Halo5\CustomTraits;

trait Stats {

    /**
     * @param $k integer
     * @param $d integer
     * @param bool $formatted
     * @return float
     */
    public function stat_kd($k, $d, $formatted = true)
    {
        if ($formatted)
            return number_format($this->_raw_kd($k, $d), 2);
        else
            return $this->_raw_kd($k, $d);
    }

    /**
     * @param $k integer
     * @param $d integer
     * @param $a integer
     * @param bool $formatted
     * @return float
     */
    public function stat_kad($k, $d, $a, $formatted = true)
    {
        if ($formatted)
        {
            return number_format($this->_raw_kad($k, $d, $a), 2);
        }
        else
        {
            return $this->_raw_kad($k, $d, $a);
        }
    }

    /**
     * @param $won integer
     * @param $total integer
     * @return mixed
     */
    public function stat_winRate($won, $total)
    {
        if ($total == 0)
        {
            return 0;
        }
        
        return round(($won / $total) * 100);
    }

    /**
     * @param $won integer
     * @param $total integer
     * @return string
     */
    public function stat_winRateColor($won, $total)
    {
        $rate = $this->winRate($won, $total);

        switch (true)
        {
            case $rate > 75:
                return 'green';

            case $rate <= 75 && $rate > 55:
                return 'yellow';

            case $rate <= 55 && $rate > 30:
                return 'orange';

            default:
                return 'red';
        }
    }

    /**
     * @param $percent
     * @return string
     */
    public function stat_percentileColor($percent)
    {
        switch (true)
        {
            case $percent > 80:
                return 'green';
            
            case $percent <= 80 && $percent > 60:
                return 'yellow';
            
            case $percent <= 60 && $percent > 40:
                return 'orange';

            case $percent == null || $percent == "?":
                return '';

            default:
                return 'red';
        }
    }

    /**
     * @param $k integer
     * @param $d integer
     * @return float
     */
    private function _raw_kd($k, $d)
    {
        if ($d == 0)
        {
            return $k;
        }

        return $k / $d;
    }

    /**
     * @param $k integer
     * @param $d integer
     * @param $a integer
     * @return float
     */
    private function _raw_kad($k, $d, $a)
    {
        if ($d == 0)
        {
            return $k + $a;
        }

        return ($k + $a) / $d;
    }
}