<?php

function print_directory($group) {
	$args = array(
		'post_type' => 'person',
		'subgroup' => 'developers',
	);
	$people = get_posts($args);
	var_dump($people);
	return "";
}