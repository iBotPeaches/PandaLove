<?php

declare(strict_types=1);

namespace Onyx\Coinmarket\Helpers\Bot;

class MessageGenerator
{
    /**
     * @param array $data
     *
     * @return string
     */
    public static function generateTickerMessage(array $data): string
    {
        $output = $data['name'].' ('.$data['symbol'].') <br />';
        $output .= '<strong>$'.$data['price_usd'].'</strong>';

        return $output;
    }
}
