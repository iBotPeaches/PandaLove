<?php namespace Onyx\Laravel\Helpers;

class Text {
    /**
     * @param $num
     * @return string
     * @throws \Exception
     */
    public static function numberToWord($num)
    {
        switch ($num)
        {
            case 1: return 'one';
            case 2: return 'two';
            case 3: return 'three';
            case 4: return 'four';
            case 5: return 'five';
            case 6: return 'six';
            case 7: return 'seven';
            case 8: return 'eight';
            case 9: return 'nine';
            case 10: return 'ten';
            case 11: return 'eleven';
            case 12: return 'twelve';
            
            default:
                throw new \Exception('Number to name matching not found. Add a rule for: ' . $num);
        }
    }
}