<?php

use NCState\ServiceFactory;
use NCState\Publications\Citation;

if (! function_exists('have_profile_publications')) {

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
