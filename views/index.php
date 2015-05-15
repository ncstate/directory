<?php
	//include 'directory_listing.php';

    $arqs = array(
            'post_type'       => 'person',
            'subgroup' 	  => get_query_var('term'),
			'nopaging'		  => true,
    );
    $query = new WP_Query($arqs);
    $people = $query->posts;

?>

<?php  get_header(); ?>

<body>
<?php include get_template_directory() . '/masthead.php'; ?>

<div id="main-content" role="main">

<!-- Remove for custom HP -->
<section class="text-mod" id="events-plugin">
  <div class="container">
    <div class="section-txt">
	<h1><?php echo (single_cat_title('',false)) ? single_cat_title('', false) : 'Events';?></h1>
	<?php foreach($people as $person):
		echo print_person($person);
	endforeach; ?>	
    </div>
  </div>
</section>
<!-- End remove -->

</div>

<?php get_footer(); ?>
