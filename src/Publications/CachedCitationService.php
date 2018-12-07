<?php

namespace NCState\Publications;

use NCState\Services\Cache;

class CachedCitationService implements CitationService
{
    /**
     * @var CitationService
     */
    private $service;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache, CitationService $service)
    {
        $this->service = $service;
        $this->cache = $cache;
    }

    /**
     * Returns an array of Citations matching an author by their
     * SPR-internal author identifier.
     *
     * @param int $authorIdentifier
     * @param int $limit default 10
     *
     * @return Citation[]
     */
    public function getCitationsByAuthorId($authorIdentifier, $limit = 10)
    {
        return $this->cache->remember("profile.publications.{$authorIdentifier}.{$limit}", $this->getTtlMinutes(), function() use ($authorIdentifier, $limit) {
            return $this->service->getCitationsByAuthorId($authorIdentifier, $limit);
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
