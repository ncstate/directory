<?php

namespace NCState\Publications;

interface CitationService
{

    /**
     * Returns an array of Citations matching an author by their
     * SPR-internal author identifier.
     *
     * @param int $authorIdentifier
     *
     * @return Citation[]
     */
    public function getCitationsByAuthorId($authorIdentifier);

}
