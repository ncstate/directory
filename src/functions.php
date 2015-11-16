<?php

use NCState\Grants\Grant;
use NCState\ServiceFactory;
use NCState\Publications\Citation;

if (! function_exists('have_profile_publications')) {

    /**
     * @return bool
     */
    function have_profile_publications()
    {
        return count(the_profile_publications()) > 0;
    }

}

if (! function_exists('the_profile_publications')) {

    /**
     * @return Citation[]
     */
    function the_profile_publications($post_id = null)
    {
        if (is_null($post_id)) {
            $postId = get_the_ID();
        } else {
            $postId = $post_id;
        }

        $sprAuthorIdentifier = get_post_meta($postId, 'spr_author_id', true);

        if (! $sprAuthorIdentifier) {
            return [];
        }

        $service = ServiceFactory::makeCitationService();

        try {
            return $service->getCitationsByAuthorId($sprAuthorIdentifier);
        } catch (Exception $e) {
            return [];
        }
    }

}

if (! function_exists('have_profile_grants')) {

    /**
     * @return bool
     */
    function have_profile_grants()
    {
        return count(the_profile_grants()) > 0;
    }

}

if (! function_exists('the_profile_grants')) {

    /**
     * @return Grant[]
     */
    function the_profile_grants($post_id = null)
    {
        if (is_null($post_id)) {
            $postId = get_the_ID();
        } else {
            $postId = $post_id;
        }

        $showGrants = get_post_meta($postId, 'show_grants', true) == '0';
        if (! $showGrants) {
            return [];
        }

        $unityIdentifier = get_post_meta($postId, 'uid', true);
        if (! $unityIdentifier) {
            return [];
        }

        $service = ServiceFactory::makeGrantService();

        try {
            return $service->getGrantsByUnityId($unityIdentifier);
        } catch (Exception $e) {
            return [];
        }
    }

}