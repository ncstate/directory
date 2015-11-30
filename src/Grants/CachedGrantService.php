<?php

namespace NCState\Grants;

use NCState\Services\Cache;

class CachedGrantService implements GrantService
{
    /**
     * @var GrantService
     */
    private $service;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache, GrantService $service)
    {
        $this->service = $service;
        $this->cache = $cache;
    }

    /**
     * Returns an array of Grants matching an author by their
     * Unity identifier.
     *
     * @param string $unityIdentifier
     *
     * @return Grant[]
     */
    public function getGrantsByUnityId($unityIdentifier)
    {
        return $this->cache->remember("profile.grants.{$unityIdentifier}", $this->getTtlMinutes(), function() use ($unityIdentifier) {
            return $this->service->getGrantsByUnityId($unityIdentifier);
        });
    }

    /**
     * @return int time, in whole minutes, to keep cached values
     */
    private function getTtlMinutes()
    {
        return 60 * 12;
    }
}
