<?php namespace Onyx\Halo5\Collections;

use Illuminate\Support\Collection;
use Onyx\Halo5\Client;

/**
 * Class LeaderboardCollection
 * @package Onyx\Halo5\Collections
 */
class LeaderboardCollection extends Collection
{
    public function __construct($results)
    {
        $items = [];
        $client = new Client();
        
        foreach ($results as $result)
        {
            $item['gamertag'] = $result['Player']['Gamertag'];
            $item['rank'] = $result['Rank'];
            $item['csr'] = [
                'tier' => $result['Score']['Tier'],
                'designationId' => $result['Score']['DesignationId'],
                'csr' => $result['Score']['Csr']
            ];
            
            $items[] = $item;
        }

        usort($items, function($a, $b)
        {
            return $a['rank'] - $b['rank'];
        });

        parent::__construct($items);
    }
}