<?php

namespace NCState\Grants;

interface GrantService
{

    /**
     * Returns an array of Grants matching an author by their
     * Unity identifier.
     *
     * @param string $unityIdentifier
     *
     * @return Grant[]
     */
    public function getGrantsByUnityId($unityIdentifier);

}
