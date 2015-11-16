<?php

namespace NCState;

use NCState\Publications\CachedCitationService;
use NCState\Publications\RestfulCitationService;
use NCState\Services\Cache;

class ServiceFactory
{
    /**
     * @return CitationService
     */
    public static function makeCitationService()
    {
        return new CachedCitationService(
            new Cache(),
            new RestfulCitationService()
        );
    }
}