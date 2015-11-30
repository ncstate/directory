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
     *
     * @return Citation[]
     */
    public function getCitationsByAuthorId($authorIdentifier)
    {
        return $this->cache->remember("profile.publications.{$authorIdentifier}", $this->getTtlMinutes(), function() use ($authorIdentifier) {
            return $this->service->getCitationsByAuthorId($authorIdentifier);
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
