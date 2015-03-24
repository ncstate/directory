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
		$image = wp_get_attachment_image_src($meta['image'][0], 'full');
		if(!$image) {
			$image[0] = plugins_url() . '/ncstate-directory/img/generic-avatar.jpg';
		}
		if(strlen($meta['phone'][0])==10) {
			$meta['phone'][0] = substr($meta['phone'][0],0,3) . "." . substr($meta['phone'][0],3,3) . "." . substr($meta['phone'][0],6);
		}
		$return_value .= '
			<div class="directory_entry">
				<a href="' . get_site_url() . '/person/' . $person->post_name . '">
					<img src="' . $image[0] . '" class="img-responsive" />
				</a>
				<div class="person_info">
					<a href="' . get_site_url() . '/person/' . $person->post_name . '"><p class="name">' . $meta['first_name'][0] . ' ' . $meta['last_name'][0] .'</p></a>
					<p class="title">' . $meta['title'][0] . '</p>
					<a href="mailto:' . $meta['email'][0] . '"</a><p class="email">' . $meta['email'][0] . '</p></a>
					<p class="phone">' . $meta['phone'][0] . '</p>
				</div>
			</div>
			';
	endforeach;
	return $return_value;
}