<?php get_header(); ?>

<body>

<?php
    /*
     * TODO
     *
     * I'm doing the scout "leave it better than I found it" here, but what I'd really
     * like here is to have ALL of this mess cleaned up outside the template. Give me a
     * call for `get_current_person` that gives me a "Person" object or something and I can
     * retrieve things like this basic profile information as well as grants and publications.
     *
     * We're getting there!
     */
    $meta = get_post_meta(get_the_ID());
    $first_name = isset($meta['first_name']) ? $meta['first_name'][0] : null;
    $last_name = isset($meta['last_name']) ? $meta['last_name'][0] : null;
    $image = isset($meta['image']) ? wp_get_attachment_image_src($meta['image'][0], 'full') : null;
    $title = isset($meta['title']) ? $meta['title'][0] : null;
    $email = isset($meta['email']) ? $meta['email'][0] : null;
    $phone = isset($meta['phone']) ? $meta['phone'][0] : null;
?>

<div id="main-content">
    <section class="text-mod">
        <div id="person">
        <div class="container">
            <div class="section-txt">
                <h1 class="section-head"><?php echo $first_name . ' ' . $last_name; ?></h1>

                <?php if($image): ?>
                <img src="<?php echo $image; ?>" />
                <?PHP endif; ?>

                <div class="person_info">
                    <?php if($title):?><p class="title"><?php echo $title; ?></p><?php endif;?>
                    <?php if($email):?><p class="email"><?php echo $email; ?></p><?php endif;?>
                    <?php if($phone):?><p class="phone"><?php echo $phone; ?></p><?php endif;?>
                </div>

                <div class="bio">
                    <?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
                        <p><?php echo get_the_content(); ?></p>
                    <?php endwhile; endif; ?>
                </div>

                <?php if(have_profile_publications()): ?>
                <ul class="publication">
                    <?php
                        foreach (the_profile_publications() as $citation) {
                            echo "<li><a href='{$citation->getLinkToLibraryCitation()}'>{$citation->title}</a></li>";
                        }
                    ?>
                </ul>
                <?php endif;?>
            </div>
        </div>
    </div>
    </section>
</div>
 
<?php get_footer(); ?>