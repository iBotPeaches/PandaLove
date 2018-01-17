<?php

namespace Onyx\Fortnite;

use Onyx\Account;
use Onyx\Fortnite\Helpers\Network\Http;
use Onyx\Fortnite\Objects\Stats;

/**
 * Class Client.
 */
class Client extends Http
{
    /**
     * @param Account $account
     * @param string $id
     * @return array
     * @throws \Exception
     */
    public function getAccountRoyaleStats(Account $account, string $id)
    {
        $url = sprintf(Constants::$PvP, $id);

        $data = $this->getJson($url);
        $platform = $this->getPlatformType($data);

        $normalized = $this->statNormalizer($data, $platform);

        return $normalized;
    }

    /**
     * @param string $name
     * @param string $platform
     * @return array
     */
    public function getAccountByTag(string $name, string $platform): array
    {
        $url = sprintf(Constants::$lookup, $name);

        $data = $this->getJson($url);

        return [$data['id'], new Account()];
    }

    //---------------------------------------------------------------------------------
    // Private Functions
    //---------------------------------------------------------------------------------

    private function getStatsModel(string $id): Stats
    {
        // TODO create Stat model
    }

    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    private function getPlatformType(array $data): string
    {
        $types = ['xb1', 'pc', 'psn'];

        $activeType = null;
        foreach ($data as $item) {
            foreach ($types as $type) {
                if ($item['name'] === 'br_kills_' . $type . '_m0_p2') {
                    return $type;
                }
            }
        }

        throw new \Exception('Could not identify platform of response.');
    }

    /**
     * @param array $data
     * @param string $platform
     * @return array
     * @throws \Exception
     */
    private function statNormalizer(array $data, string $platform): array
    {
        $stats = [];

        foreach ($data as $item) {
            $key = $item['name'];
            $type = $this->getSquadType($key);
            $stat = $this->getStatType($key);

            $stats[$platform][$type][$stat][$item['window']] = $item['value'];
        }

        return $stats;
    }

    /**
     * @param string $type
     * @return string
     */
    private function getSquadType(string $type): string
    {
        switch (true) {
            case ends_with($type, '_p2'):
                return 'solo';
            case ends_with($type, '_p10'):
                return 'duo';
            default:
                return 'squad';
        }
    }

    /**
     * @param string $type
     * @return string
     * @throws \Exception
     */
    private function getStatType(string $type): string
    {
        switch (true) {
            case str_contains($type, 'kills'):
                return 'kills';
            case str_contains($type, 'minutesplayed'):
                return 'minutesplayed';
            case str_contains($type, 'matchesplayed'):
                return 'matchesplayed';
            case str_contains($type, 'lastmodified'):
                return 'lastmodified';
            case str_contains($type, 'score'):
                return 'score';

            case str_contains($type, 'placetop25'):
                return 'top25';
            case str_contains($type, 'placetop10'):
                return 'top10';
            case str_contains($type, 'placetop12'):
                return 'top12';
            case str_contains($type, 'placetop6'):
                return 'top6';
            case str_contains($type, 'placetop5'):
                return 'top5';
            case str_contains($type, 'placetop3'):
                return 'top3';
            case str_contains($type, 'placetop1'):
                return 'top1';

            default:
                throw new \Exception('Unknown new stat - ' . $type);
        }
    }

}
