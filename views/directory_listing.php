<?php

function print_directory($group) {
	$args = array(
		'post_type' => 'person',
		'subgroup' => $group,
	);
	$people = get_posts($args);
	$return_value = '';
	foreach($people as $person) :
		$meta = get_post_meta($person->ID);
		//echo '<pre>';
		//var_dump($meta);
		//echo '</pre>';
		$return_value .= '
			<div class="directory_entry">
				<p class="name">' . $meta['first_name'][0] . ' ' . $meta['last_name'][0] .'</p>
				<p class="title">' . $meta['title'][0] . '</p>
				<p class="email">' . $meta['email'][0] . '</p>
				<p class="phone">' . $meta['phone'][0] . '</p>
			</div>
			';
	endforeach;
	return $return_value;
}