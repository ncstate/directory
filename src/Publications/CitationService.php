<?php

namespace NCState\Publications;

interface CitationService
{

    /**
     * Returns an array of Citations matching an author by their
     * SPR-internal author identifier.
     *
     * @param int $authorIdentifier
     * @param int $limit default 10
     *
     * @return Citation[]
     */
    public function getCitationsByAuthorId($authorIdentifier, $limit = 10);

}
