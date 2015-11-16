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
        return $this->cache->remember("profile.grants.{$unityIdentifier}", 60 * 12, function() use ($unityIdentifier) {
            return $this->service->getGrantsByUnityId($unityIdentifier);
        });
    }
}
