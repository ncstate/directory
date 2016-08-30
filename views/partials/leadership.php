<?php

function get_leaders_html($page_id) {
	
	if(!empty($_POST['directory_search'])) {
		return '';
	}

	$leadership .= '<div class="leadership">';
	$leadership .= '<h2>Leadership</h2>';

	$leaders = get_post_meta($page_id, 'leadership', true);
	$categories = get_post_meta($page_id, 'listed_categories', true);
	$layout = get_post_meta($page_id, 'display_type', true);

	foreach($leaders as $leader) {
		$leadership .= print_person(get_post($leader), $categories, $layout);
	}

	$leadership .= '</div>';
	
	return $leadership;

}