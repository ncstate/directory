<?php

function print_directory($group) {
	$args = array(
		'post_type' => 'person',
		'subgroup' => $group,
		'nopaging'	=> true,
	);
	$people = get_posts($args);
	$return_value = '';
	foreach($people as $person) :
		$return_value .= print_person($person);
	endforeach;
	return $return_value;
}

function print_person($person) {
	$meta = get_post_meta($person->ID);
	if (empty($meta['first_name'][0]))
		return;
	
	$image = wp_get_attachment_image_src($meta['image'][0], 'full');
	if($image) {
		$img_tag = '<img src="' . $image[0] . '" class="img-responsive" />';
	} else {
		$img_tag = '<div class="initials">' . substr($meta['first_name'][0], 0, 1) . substr($meta['last_name'][0], 0, 1) . '</div>';
	}
	if(strlen($meta['phone'][0])==10) {
		$meta['phone'][0] = substr($meta['phone'][0],0,3) . "." . substr($meta['phone'][0],3,3) . "." . substr($meta['phone'][0],6);
	}
	$return_value .= '
		<div class="directory_entry">
			<a href="' . get_site_url() . '/person/' . $person->post_name . '">
				' . $img_tag . '
			</a>
			<div class="person_info">
				<p class="name"><b>' . $meta['first_name'][0] . ' ' . $meta['last_name'][0] .'</b>' . $dean_bio . '</p>
				<p class="title">' . $meta['title'][0] . '</p>
				<a href="mailto:' . $meta['email'][0] . '"</a><p class="email">' . $meta['email'][0] . '</p></a>
				<p class="phone">' . $meta['phone'][0] . '</p>
			</div>
		</div>
		';
	return $return_value;
}
