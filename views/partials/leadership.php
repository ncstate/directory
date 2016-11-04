<?php

function get_leaders_html() {
	
	$leaders = explode(",", get_option('ncstate_directory_leaders', ''));
	$categories = explode(",", get_option('ncstate_directory_displayed_subgroups_in_index', ''));
	$layout = get_option('ncstate_directory_index_view_type', 'row');
	
	if(empty($leaders)) {
		return;
	}
	
	$group = (get_query_var('term')) ? get_query_var('term') : false;
	
	if(!empty($_POST['directory_search'] || !empty($group))) {
		return '';
	}

	$leadership .= '<div class="leadership">';
	$leadership .= '<h2>Leadership</h2>';

	foreach($leaders as $leader) {
		$leader = get_page_by_path($leader, OBJECT, 'person');
		$leadership .= print_person($leader, $categories, $layout);
	}

	$leadership .= '</div>';
	
	return $leadership;

}