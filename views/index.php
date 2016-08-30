<?php

include_once 'partials/leadership.php';

    global $wp_query;

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $group = (get_query_var('term')) ? get_query_var('term') : false;
	$subgroup = (get_query_var('subgroup')) ? get_query_var('subgroup') : false;
	$page_id = get_page_by_title('People');
	$page_id = $page_id->ID;
	$layout = get_post_meta($page_id, 'display_type', true);

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

<?php get_header(); ?>

<body>

<?php get_template_part('masthead'); ?>

<div id="main-content" role="main">

<section class="text-mod no-components" id="directory-index">
	<div class="container">
		<div class="section-txt">
			<h1 class="section-head"><?php echo (single_cat_title('',false)) ? single_cat_title('', false) : 'Directory';?></h1>

			<p class="lead"><?php echo get_post_meta($page_id, 'introduction', true); ?></p>

			<div class="row">

				<div class="controls">
					<form method="post">
						<input type="search" name="directory_search" placeholder="Search directory" />
						<button type="submit" class="btn btn-red btn-shortcode"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</form>
				
					<div class="dropdown">
						<ul class="dropdown">
							<?php
								if($subgroup):
									$term_name = get_term_by('slug', $subgroup, 'subgroup');
									echo '<li class="active"><span class="active">' . $term_name->name . '<span class="glyphicon glyphicon-down-bracket"></span></span></li>';
								else:
									echo '<li class="active"><span class="active">Filter<span class="glyphicon glyphicon-down-bracket"></span></span></li>';
								endif;
							?>

							<?php
								$terms = get_post_meta($page_id, 'category_choices', true);
								foreach($terms as $term):
									$term_info = get_term_by('id', $term, 'subgroup');
									if($term_info->slug != $subgroup):
										echo '<li><a href="' . get_term_link($term_info->term_id) . '">' . $term_info->name . '</a></li>';
									endif;
								endforeach;
							?>
						</ul>
					</div>
				</div>

			<?php 
			if ( $wp_query->have_posts() ) :

				$people = '';

				while( $wp_query->have_posts() ):
					$wp_query->the_post();
					
					$person = get_post();
					
					$person_meta = get_post_meta($person->ID);
					
					if($layout == 'row'):
						if(substr($person_meta['last_name'][0], 0, 1) > $last_letter && empty($_POST['directory_search'])):
							// Prints out anchor point and letter for directory listing
							$people .= '<a name="' . substr($person_meta['last_name'][0], 0, 1) . '"></a><h2 class="letter">' . substr($person_meta['last_name'][0], 0, 1) . '</h2><a href="#main-content" class="back-to-top">Back to Top <span class="glyphicon glyphicon-up-thin-arrow"></span></a>';
						endif;
						$last_letter = substr($person_meta['last_name'][0], 0, 1);
						$alphabet[ord($last_letter)-65][1] = true;
						$categories_to_list = get_post_meta($page_id, 'listed_categories', true);
					endif;
					
					$people .= print_person($person, $categories_to_list, $layout);
				endwhile;

				if($layout == 'row'):
					echo '<div class="alphabet">';
					echo '<p>Jump to:</p>';
					foreach(range('A','Z') as $letter) {
						echo '<a href="#' . $letter . '">' . $letter . '</a>';
					}
					echo '</div>';
				endif;

				echo get_leaders_html($page_id);

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
		</div>
	</div>
</section>
<!-- End remove -->

</div>

<?php get_footer(); ?>

<script type="text/javascript">
	$('ul.dropdown').click(function() {
		$('ul.dropdown li').toggle();
	});
</script>
