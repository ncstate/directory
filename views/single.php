<?php get_header(); ?>
<?php if(have_posts()): while(have_posts()): the_post(); ?>
<?php

    $meta = get_post_meta(get_the_ID());

    if (empty($meta['first_name'][0])) {
        return;
    }

    if (isset($meta['image'][0])) {
        $image = wp_get_attachment_image_src( $meta['image'][0], 'profileThumb' );
    }

    if (! empty($image)) {
        $img_tag = '<img src="' . $image[0] . '" class="img-responsive" />';
    } else {
        $img_tag = '<div class="initials text-primary hidden-xs">' . substr($meta['first_name'][0], 0, 1) . substr($meta['last_name'][0], 0, 1) . '</div>';
    }

    if (strlen($meta['phone'][0])==10) {
        $meta['phone'][0] = substr($meta['phone'][0],0,3) . "." . substr($meta['phone'][0],3,3) . "." . substr($meta['phone'][0],6);
    }
?>

    <article class="post type-post format-standard hentry">
        <div class="entry-content">
            <h1><?php echo $meta['first_name'][0] . ' ' . $meta['last_name'][0]; ?></h1>
            <h2 class="title"><?php echo $meta['title'][0]; ?></h2>
            <ul class="contact-details">
                <li class="phone"><?php echo $meta['phone'][0]; ?></li>
                <li class="email"><?php echo $meta['email'][0]; ?></li>
                <li class="office"><?php echo $meta['office'][0]; ?></li>
                <?php if(! empty($meta['website'][0])) : ?>
                    <li class="website"><a href="<?php echo $meta['website'][0]; ?>" target="_blank">Visit My Website</a></li>
                <?php endif; ?>
            </ul>

            <?php if (! empty(get_the_content())): ?>
            <div class="bio">
                <?php the_content(); ?>
            </div>
            <?php endif;?>

            <?php if(have_profile_publications()): ?>
            <h3>Publications</h3>
            <ul class="publication">
                <?php
                    foreach (the_profile_publications() as $citation) {
                        echo "<li><a href='{$citation->getLinkToLibraryCitation()}'>{$citation->title} ({$citation->year})</a></li>";
                    }
                ?>
            </ul>
            <?php endif;?>
        </div>
    </article>
    
<?php endwhile; endif; ?>
<?php get_footer(); ?>
