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
		//var_dump($person);
		//echo '</pre>';
		$image = wp_get_attachment_image_src($meta['image'][0], 'full');
		$return_value .= '
			<div class="directory_entry">
				<img src="' . $image[0] . '" />
				<div class="person_info">
					<a href="' . get_site_url() . '/person/' . $person->post_name . '"><p class="name">' . $meta['first_name'][0] . ' ' . $meta['last_name'][0] .'</p></a>
					<p class="title">' . $meta['title'][0] . '</p>
					<p class="email">' . $meta['email'][0] . '</p>
					<p class="phone">' . $meta['phone'][0] . '</p>
					<p class="website">scott-thompson.com</p>
				</div>
			</div>
			';
	endforeach;
	return $return_value;
}