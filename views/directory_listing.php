<?php

function print_directory($group) {
	$args = array(
		'post_type' => 'person',
		'subgroup' => $group,
		'nopaging'	=> true,
		'meta_key'  => 'last_name',
		'orderby' => 'meta_value',
		'order' => 'ASC',
	);

	$people = get_posts($args);

	$return_value = '';
	foreach ($people as $person) {
		$return_value .= print_person($person);
	}

	return $return_value;
}

function print_person($person, $categories = null) {
	$return_value = '';
	$meta = get_post_meta($person->ID);

	if (empty($meta['first_name'][0])) {
		return;
	}

	$image = wp_get_attachment_image_src($meta['image'][0], 'full');
	if($image) {
		$img_tag = '<img src="' . $image[0] . '" class="img-responsive" />';
	} else {
		//$img_tag = '<div class="initials">' . substr($meta['first_name'][0], 0, 1) . substr($meta['last_name'][0], 0, 1) . '</div>';
	}

	if(strlen($meta['phone'][0])==10) {
		$meta['phone'][0] = substr($meta['phone'][0],0,3) . "." . substr($meta['phone'][0],3,3) . "." . substr($meta['phone'][0],6);
	}
	
	$terms = wp_get_post_terms($person->ID, 'subgroup');
	
	$subgroup_listing = array();
	foreach($terms as $term) {
		if(!empty($categories) && in_array($term->term_id, $categories)) {
			$subgroup_listing[] = $term->name;
		}
	}

	$return_value .= '
		<div class="directory_entry row">
			<a href="' . get_site_url() . '/people/' . $person->post_name . '">
				' . $img_tag . '
			</a>
			<div class="person_info">
				<a href="' . get_site_url() . '/people/' . $person->post_name . '">
					<p class="name"><b>' . $meta['first_name'][0] . ' ' . $meta['last_name'][0] .'</b>' . $dean_bio . '</p>
				</a>
				<p class="title">' . $meta['title'][0] . '</p>
				<p class="unit">' . implode(', ', $subgroup_listing) . '</p>
			</div>
			<div class="subgroups">
				<a href="mailto:' . $meta['email'][0] . '"</a><p class="email">' . $meta['email'][0] . '</p></a>
				<p class="phone">' . $meta['phone'][0] . '</p>
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

	$site_url = get_site_url();
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
				<a href='{$site_url}/people/{$person->post_name}' target='_blank'>{$first_name} {$last_name}</a>
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