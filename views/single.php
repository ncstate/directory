<?php get_header(); ?>

<body>
	
<?php include get_template_directory() . '/masthead.php'; ?>
<div id="main-content">
	<div id="person">
		<div class="container">
			<?php $meta = get_post_meta(get_the_ID()); ?>
			<h1><?php echo $meta['first_name'][0] . ' ' . $meta['last_name'][0]; ?></h1>
			<?php $image = wp_get_attachment_image_src($meta['image'][0]); ?>
			<img src="<?php echo $image[0]; ?>" />
			<p class="title"><?php echo $meta['title'][0]; ?></p>
			<p class="email"><?php echo $meta['email'][0]; ?></p>
			<p class="phone"><?php echo $meta['phone'][0]; ?></p>
			<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>	
				<p><?php echo get_the_content(); ?></p>
			<?php endwhile; endif; ?>
		</div>
	</div>
</div>
 
<?php get_footer(); ?>