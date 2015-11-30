<?php

namespace NCState;

use NCState\Grants\CachedGrantService;
use NCState\Services\Cache;
use NCState\Grants\GrantService;
use NCState\Publications\CitationService;
use NCState\Grants\RestfulGrantService;
use NCState\Publications\RestfulCitationService;
use NCState\Publications\CachedCitationService;

class ServiceFactory
{
    /**
     * @return CitationService
     */
    public static function makeCitationService()
    {
        return new CachedCitationService(new Cache(), new RestfulCitationService());
    }

    /**
     * @return GrantService
     */
    public static function makeGrantService()
    {
        return new CachedGrantService(new Cache(), new RestfulGrantService());
    }
}