<?php

function print_directory($group, $layout = 'grid') {
	$args = array(
		'post_type' => 'person',
		'subgroup' => $group,
		'nopaging'	=> true,
		'meta_key'  => 'last_name',
		'orderby' => 'meta_value',
		'order' => 'ASC',
	);

	$people = get_posts($args);

	$return_value = '<div class="ncstate-directory">';
	foreach ($people as $person) {
		$return_value .= print_person($person, null, $layout);
	}
	$return_value .= '</div>';

	return $return_value;
}

function print_person($person, $categories = null, $layout = 'grid') {
	$return_value = '';
	$meta = get_post_meta($person->ID);

	if (empty($meta['first_name'][0])) {
		return;
	}

	$image = wp_get_attachment_image_src($meta['image'][0], 'full');
	$image_alt = get_post_meta($meta['image'][0], '_wp_attachment_image_alt', TRUE);
	if(get_option('ncstate_directory_display_images', 'true') == 'false') {
		$img_code = '';
	} elseif($image) {
		$img_code = '<a href="' . get_site_url() . '/people/' . $person->post_name . '"><img src="' . $image[0] . '" class="img-responsive" alt="' . $image_alt . '" /></a>';
	} else {
		$img_code = '<a href="' . get_site_url() . '/people/' . $person->post_name . '"><div class="initials">' . substr($meta['first_name'][0], 0, 1) . substr($meta['last_name'][0], 0, 1) . '</div></a>';
	}

	if(strlen($meta['phone'][0])==10) {
		$meta['phone'][0] = substr($meta['phone'][0],0,3) . "." . substr($meta['phone'][0],3,3) . "." . substr($meta['phone'][0],6);
	}
	
	$terms = wp_get_post_terms($person->ID, 'subgroup');
	
	$subgroup_listing = array();
	foreach($terms as $term) {
		if(!empty($categories) && in_array($term->slug, $categories)) {
			$subgroup_listing[] = $term->name;
		}
	}
	
	if(!empty($meta['email'][0])):
		$email = '<a href="mailto:' . $meta['email'][0] . '"</a><p class="email">' . $meta['email'][0] . '</p></a>';
	else:
		$email = '';
	endif;
	
	if(!empty($meta['phone'][0])):
		$phone = '<p class="phone">' . $meta['phone'][0] . '</p>';
	else:
		$email = '';
	endif;

	$return_value .= '
		<div class="directory_entry ' . $layout . '">
			' . $img_code . '
			<div class="person_info">
				<a href="' . get_site_url() . '/people/' . $person->post_name . '">
					<p class="name">' . $meta['first_name'][0] . ' ' . $meta['last_name'][0] .'' . $dean_bio . '</p>
				</a>
				<p class="title">' . $meta['title'][0] . '</p>
				<p class="unit">' . implode(', ', $subgroup_listing) . '</p>
			</div>
			<div class="subgroups">
				' . $email . '
				' . $phone . '
			</div>
		</div>
	';

	return $return_value;
}

function print_directory_list($group, $columns) {
	$args = array(
		'post_type' => 'person',
		'subgroup' => $group,
		'nopaging'	=> true,
		'meta_key'  => 'last_name',
		'orderby' => 'meta_value',
		'order' => 'ASC',
	);

	$home_url = get_home_url();
	$people = get_posts($args);
	$num_people = count($people);
	$people_per_column = floor($num_people/$columns);

	if($num_people % $columns > 0) {
		$people_per_column = $people_per_column + 1;
	}
	$columns_class = 'col-md-'.floor(12/$columns);

	$return_value = "
		<div class='row'>
			<div class='{$columns_class}'>
				<ul class='directory-list'>
	";

	$iterator_count = 0;
	$columns_complete = 0;

	foreach($people as $person) {
		$meta = get_post_meta($person->ID);
		$first_name = $meta['first_name'][0];
		$last_name = $meta['last_name'][0];

		if (empty($first_name)) {
			return;
		}

		$return_value .= "
			<li>
				<a href='{$home_url}/people/{$person->post_name}' target='_blank'>{$first_name} {$last_name}</a>
			</li>
		";

		$iterator_count++;
		if ($iterator_count == $people_per_column) {
			$iterator_count = 0;
			$columns_complete++;
			if ($columns_complete < $columns) {
				$return_value .= "</ul></div>";
				$return_value .= "<div class='{$columns_class}'><ul class='directory-list'>";
			}
		}
	}

	// Close ending elements...
	$return_value .= "
				</ul>
			</div>
		</div>
	";

	return $return_value;
}