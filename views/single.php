<?php get_header(); ?>

<body id="<?php echo seoUrl(get_the_title()); ?>-page">
	
<?php include get_template_directory() . '/masthead.php'; ?>
<div id="main-content">
	
	<div class="container">
	    <div class="row">
	        <div class="subpage-content">
	<section class="text-mod">
		<div id="person">
		
			<?php if(have_posts()): while(have_posts()): the_post(); ?>
			<?php $meta = get_post_meta(get_the_ID()); ?>
			<div class="section-txt">
				<h1 class="section-head"><?php echo $meta['first_name'][0] . ' ' . $meta['last_name'][0]; ?></h1>
				
				<?php
					$image = wp_get_attachment_image_src($meta['image'][0], 'full');
					if($image):
						echo '<img src="' . $image[0] . '" class="img-responsive" />';
					endif;
						
					if(strlen($meta['phone'][0])==10):
						$meta['phone'][0] = substr($meta['phone'][0],0,3) . "." . substr($meta['phone'][0],3,3) . "." . substr($meta['phone'][0],6);
					endif;

				?>

				<div class="person_info">
					<div class="titles">
						<p class="title"><?php echo $meta['title'][0]; ?></p>
						<?php if(have_rows('additional_titles')): ?>
							<?php while(have_rows('additional_titles')): the_row();?>
								<p class="additional_titles"><?php echo get_sub_field('title'); ?></p>
							<?php endwhile; ?>
						<?php endif; ?>
					</div>
					<p class="office"><?php echo $meta['office'][0]; ?></p>
					<p class="email"><a href="mailto:<?php echo $meta['email'][0]; ?>"><?php echo $meta['email'][0]; ?></a></p>
					<p class="phone"><?php echo '<a href="tel:' . $meta['phone'][0] . '">' . $meta['phone'][0] ?></a></p>
					<p class="website"><a href="<?php echo $meta['website'][0]; ?>" target="_blank"><?php echo $meta['website'][0]; ?></a></p>
				</div>
			</div>
		</div>
	</section>
	
	<section class="text-mod no-components">
        <div class="container">
			<div class="section-txt">     
         
            	<?php 
                	if(get_the_content()!='') :     
						echo '<h3>Bio</h3><p class="biography">';
						the_content();
						echo '</p>';
					endif; 
				?>	
					
		    	<?php 
                   	if($meta['research_description'][0] != NULL) :     
						echo '<h3>Research Description</h3><p class="research_description">';
						echo $meta['research_description'][0];
						echo '</p>';
					endif; 
				?>
				
				<?php
				if(get_field('google_scholar_link')):
					echo '<h3>Publications</h3>';
					echo '<p><a href="' . get_field('google_scholar_link') . '">View on Google Scholar</a></p>';
				endif;
				?>

				<?php
				if(get_field('cv')):
					echo '<h3>CV</h3>';
					echo '<p><a href="' . get_field('cv') . '">View CV</a></p>';
				endif;
				?>

				<?php 
					if(get_field('education')):
							echo '<h3>Education</h3><p class="education">';					
							while ( has_sub_field('education'))
								{
								echo '<strong>';
								echo get_sub_field('type_of_degree');
								echo '</strong>, ';
								echo get_sub_field('degree_program');
								echo ', ';
								echo get_sub_field('school');
								echo ' (';									
								echo get_sub_field('year_earned');
								echo ')<br />';		
								}
							echo "</p>";
					endif;						
				?>
				
				<?php
					if(have_rows('honors_and_awards')):
						echo '<h3>Honors and Awards</h3>';
						echo '<ul>';
						while(have_rows('honors_and_awards')): the_row();
							echo '<li>' . get_sub_field('award') . '</li>';
						endwhile;
						echo '</ul>';
					endif;
				?>

                <?php 
                  	if($meta['curriculum_vitae'][0] != NULL) : 
                		$curriculum_vitae = get_post($meta['curriculum_vitae'][0]);  
						echo '<h3>Curriculum Vitae</h3><p class="curriculum_vitae">';
						echo '<ul class=\"spotlight-list\"><li><a href="';
						echo $curriculum_vitae->guid;
						echo '">';
						echo $curriculum_vitae->post_title;
						echo '</a></li></ul></p>';
					endif; 
				?>

			</div>
		<?php endwhile; endif; ?>
		</div>
	</section>
</div>
</div>
</div> 
<?php get_footer(); ?>
