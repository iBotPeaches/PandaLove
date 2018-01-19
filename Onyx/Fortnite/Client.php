<?php

namespace Onyx\Fortnite;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Fortnite\Helpers\Network\FortniteApiNetworkException;
use Onyx\Fortnite\Helpers\Network\Http;
use Onyx\Fortnite\Objects\Stats;
use Onyx\User;
use Onyx\XboxLive\Enums\Console;

/**
 * Class Client.
 */
class Client extends Http
{
    public $userId = null;

    /**
     * @param Account $account
     * @param string  $id
     *
     * @throws FortniteApiNetworkException
     * @throws \Exception
     * @throws \Throwable
     *
     * @return Stats
     */
    public function getAccountRoyaleStats(Account $account, string $id): Stats
    {
        $url = sprintf(Constants::$PvP, $id);

        $data = $this->getJson($url);
        $platform = $this->getPlatformType($data);

        $normalized = $this->statNormalizer($data, $platform);
        $stats = $this->getStatsModel($id, $account);

        if ($this->updateStatsModel($stats, $normalized, $platform)) {
            return $stats;
        }

        throw new FortniteApiNetworkException();
    }

    /**
     * @param User $user
     */
    public function setPandaAuth(User $user)
    {
        $this->userId = $user->id;
    }

    /**
     * @param Account $account
     *
     * @throws FortniteApiNetworkException
     * @throws \Exception
     * @throws \Throwable
     *
     * @return Stats
     */
    public function updateAccount(Account $account): Stats
    {
        return $this->getAccountRoyaleStats($account, $account->fortnite->epic_id);
    }

    /**
     * @param string $id
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getPlatformViaEndpoint(string $id): string
    {
        $url = sprintf(Constants::$PvP, $id);

        $data = $this->getJson($url);

        return $this->getPlatformType($data);
    }

    /**
     * @param string $name
     * @param string $platform
     *
     * @throws FortniteApiNetworkException
     * @throws \Exception
     * @throws \Throwable
     *
     * @return array
     */
    public function getAccountByTag(string $name, string $platform): array
    {
        $url = sprintf(Constants::$lookup, $name);
        $expectedPlatform = Console::getFortniteTag($platform);
        $data = $this->getJson($url);

        if (isset($data['id'])) {
            $this->checkPlatforms($expectedPlatform, $this->getPlatformViaEndpoint($data['id']));

            // Load a specific account based on platform
            try {
                $account = Account::where('seo', Text::seoGamertag($data['displayName']))
                    ->where('accountType', $platform)
                    ->firstOrFail();
            } catch (ModelNotFoundException $ex) {
                $account = new Account([
                    'gamertag'    => $data['displayName'],
                    'accountType' => $platform,
                ]);

                $account->saveOrFail();
            }
        }

        return [$data['id'], $account ?? null];
    }

    //---------------------------------------------------------------------------------
    // Private Functions
    //---------------------------------------------------------------------------------

    /**
     * @param string $expected
     * @param string $obtained
     *
     * @throws FortniteApiNetworkException
     */
    private function checkPlatforms(string $expected, string $obtained): void
    {
        if ($expected !== $obtained) {
            throw new FortniteApiNetworkException();
        }
    }

    /**
     * @param string       $id
     * @param Account|null $account
     *
     * @return Stats
     */
    private function getStatsModel(string $id, Account $account = null): Stats
    {
        try {
            $stats = Stats::where('epic_id', $id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            $stats = new Stats([
                'epic_id' => $id,
            ]);

            if ($account !== null) {
                $stats->account_id = $account->id;
            }
        }

        return $stats;
    }

    /**
     * @param Stats  $statModel
     * @param array  $normalized
     * @param string $platform
     *
     * @throws \Throwable
     *
     * @return bool
     */
    private function updateStatsModel(Stats $statModel, array $normalized, string $platform): bool
    {
        $allowedAttributes = ['kills', 'matchesplayed', 'score', 'minutesplayed', 'lastmodified', 'top1', 'top3', 'top5',
            'top6', 'top10', 'top12', 'top25', ];

        $oldMatches = $statModel->getMatchesSum();

        foreach ($normalized[$platform] as $group => $stats) {
            foreach ($stats as $key => $item) {
                if (!in_array($key, $allowedAttributes)) {
                    continue;
                }

                $key = $group.'_'.$key;
                $statModel->setAttribute($key, $item['alltime']);
            }
        }

        // Increment inactive-ness, if nothing changes
        if ($oldMatches !== $statModel->getMatchesSum()) {
            $statModel->inactiveCounter = 0;
        } else {
            $statModel->inactiveCounter++;
        }

        // Add if Panda
        if ($this->userId !== null) {
            $statModel->user_id = $this->userId;
            $this->userId = null;
        }

        return $statModel->saveOrFail();
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     *
     * @return string
     */
    private function getPlatformType(array $data): string
    {
        $types = ['xb1', 'pc', 'psn'];

        $activeType = null;
        foreach ($data as $item) {
            foreach ($types as $type) {
                if ($item['name'] === 'br_kills_'.$type.'_m0_p2') {
                    return $type;
                }
            }
        }

        throw new \Exception('Could not identify platform of response.');
    }

    /**
     * @param array  $data
     * @param string $platform
     *
     * @throws \Exception
     *
     * @return array
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
     *
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
     *
     * @throws \Exception
     *
     * @return string
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
                throw new \Exception('Unknown new stat - '.$type);
        }
    }
}
