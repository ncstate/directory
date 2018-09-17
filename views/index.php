<?php

include_once 'partials/leadership.php';

get_header(); ?>

<body>

<?php get_template_part('masthead'); ?>

<?php

    global $wp_query;

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $group = (get_query_var('term')) ? get_query_var('term') : false;
	$subgroup = (get_query_var('subgroup')) ? get_query_var('subgroup') : false;
	$layout = get_option('ncstate_directory_index_view_type', 'row');
	if(!empty(get_option('ncstate_directory_filter_subgroups', ''))):
		$terms = explode(",", get_option('ncstate_directory_filter_subgroups', ''));
	else:
		$terms = '';
	endif;
	$repo_site = false;
	
	if(!empty(get_option('ncstate_directory_repo_site_subgroups')) && empty($group)):
		$group = get_option('ncstate_directory_repo_site_subgroups');
	endif;
	
	if(!empty(get_option('ncstate_directory_repo_site_id',''))):
		$repo_site = true;
		switch_to_blog(get_option('ncstate_directory_repo_site_id'));
	endif;
	
	$queried_object = get_queried_object();

	$args = array(
		'post_type' => 'person',
		'subgroup' => $group,
		'posts_per_page' => -1,
		'paged' => $paged,
		'meta_key' => 'last_name',
		'meta_query' => array(
			array(
				'key' => 'last_name',
				'value' => ' ',
				'compare' => '!='
			)
		),
		'orderby' => 'meta_value',
		'order' => 'ASC',
	);

	if(!empty($_POST['directory_search'])) {
		$args['s'] = $_POST['directory_search'];
	}

	$wp_query = new WP_Query($args);
	$number_of_pages = $wp_query->max_num_pages;

?>

<div id="main-content" role="main">

<section class="text-mod no-components" id="directory-index">
	<div class="container">
		<div class="section-txt">
			<?php if(($filter_page && !$repo_site) || is_tax('subgroup')): ?>
				<h1 class="section-head"><?php echo (single_cat_title('',false)) ? single_cat_title('', false) : 'Directory';?></h1>
			<?php else: ?>
				<h1 class="section-head">Directory</h1>
			<?php endif; ?>

			<?php if(is_tax('subgroup')): ?>
				<p class="lead"><?php echo term_description(); ?></p>
			<?php else: ?>
				<p class="lead"><?php echo get_option('ncstate_directory_main_intro_text', ''); ?></p>
			<?php endif; ?>
			
			<?php $filter_page = false; ?>
			<?php if(is_array($terms) && in_array($queried_object->slug, $terms)): ?>
				<?php $filter_page = true; ?>
			<?php endif; ?>
			
			<?php if(!empty($terms) && $queried_object->name == 'person'): ?>
				<?php $filter_page = true; ?>
			<?php endif; ?>

			<div class="row">

				<?php if($filter_page && !$repo_site): ?>
					<div class="controls">
					<form method="post" class="unstyled">
						<input type="search" name="directory_search" placeholder="Search directory" />
						<button type="submit" class="btn btn-red search-submit btn-shortcode"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</form>
				
					<div class="category-dropdown">
						<button type="button" class="btn btn-default category-dropdown-button" data-toggle="dropdown">Filter By<span class="glyphicon glyphicon-down-bracket"></span></button>
						<ul class="dropdown-menu">
							<li><a href="<?php echo get_post_type_archive_link( 'person' ); ?>">All</a></li>
							<?php
								foreach($terms as $term):
									$term_info = get_term_by('slug', $term, 'subgroup');
									if($term_info->slug != $subgroup):
										echo '<li><a href="' . get_term_link($term_info->term_id) . '">' . $term_info->name . '</a></li>';
									endif;
								endforeach;
							?>
						</ul>
					</div>
				</div>
				<?php endif; ?>

			<?php 
			if ( $wp_query->have_posts() ) :

				$people = '';
				while( $wp_query->have_posts() ):
					$wp_query->the_post();
					
					$person = get_post();
					
					$person_meta = get_post_meta($person->ID);
					
					if($layout == 'row' && $filter_page):
						if(substr($person_meta['last_name'][0], 0, 1) > $last_letter && empty($_POST['directory_search'])):
							// Prints out anchor point and letter for directory listing
							$people .= '<a name="' . substr($person_meta['last_name'][0], 0, 1) . '"></a><h2 class="letter">' . substr($person_meta['last_name'][0], 0, 1) . '</h2><a href="#main-content" class="back-to-top">Back to Top</a>';
						endif;
						$last_letter = substr($person_meta['last_name'][0], 0, 1);
						$alphabet[ord($last_letter)-65][1] = true;
						$categories_to_list = explode(",", get_option('ncstate_directory_displayed_subgroups_in_index', array()));
					endif;
					
					$people .= print_person($person, $categories_to_list, $layout);
				endwhile;

				if($layout == 'row' && $filter_page):
					echo '<div class="alphabet">';
					echo '<p>Jump to:</p>';
						echo '<div class="links">';
						foreach(range('A','Z') as $letter) {
							echo '<a href="#' . $letter . '">' . $letter . '</a>';
						}
						echo '</div>';
					echo '</div>';
				endif;

				echo get_leaders_html();

				echo $people;

				if ($number_of_pages > 1) :
					$args = array(
						'format' => 'page/%#%',
						'type'   => 'array',
						'show_all' => true,
						'prev_text' => '&laquo;',
						'next_text' => '&raquo;'
					);
					$links = paginate_links($args);
					
				?>
					<div class="index-nav">
						<ul class="pagination">
							<?php 
							foreach($links as $link):
								$class = (strpos($link,'current')) ? ' class="active"' : '';
								echo '<li' . $class . '>' . $link . '</li>';
							endforeach;
							?>
						</ul>
					</div>
				<?php
				endif;
			else :
				echo "<p>Sorry, no posts matched your criteria.</p>";
			endif;
			?>	
			</div>

			<?php 
				if($repo_site):
					restore_current_blog();
				endif;
			?>

		</div>
	</div>
</section>
<!-- End remove -->

</div>

<?php get_footer(); ?>
