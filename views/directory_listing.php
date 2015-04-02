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
		if($meta['hierarchy'][0]=='1'):
			$return_value .= print_person($person);
		endif;
	endforeach;
	foreach($people as $person) :
		$meta = get_post_meta($person->ID);
		if($meta['hierarchy'][0]=='2'):
			$return_value .= print_person($person);
		endif;
	endforeach;
	foreach($people as $person) :
		$meta = get_post_meta($person->ID);
		if($meta['hierarchy'][0]=='0' || $meta['hierarchy']==null):
			$return_value .= print_person($person);
		endif;
	endforeach;
	return $return_value;
}

function print_person($person) {
	$meta = get_post_meta($person->ID);
	$image = wp_get_attachment_image_src($meta['image'][0], 'full');
	if($image) {
		$img_tag = '<img src="' . $image[0] . '" class="img-responsive" />';
	} else {
		$img_tag = '<div class="initials">' . substr($meta['first_name'][0], 0, 1) . substr($meta['last_name'][0], 0, 1) . '</div>';
	}
	if(strlen($meta['phone'][0])==10) {
		$meta['phone'][0] = substr($meta['phone'][0],0,3) . "." . substr($meta['phone'][0],3,3) . "." . substr($meta['phone'][0],6);
	}
	$dean_bio='';
	if($meta['hierarchy'][0]==1) {
		$dean_bio = " (<a href='/about/staff/meet-the-dean/'>Bio</a>)";
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
