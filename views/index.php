<?php

    global $wp_query;

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $group = (get_query_var('term')) ? get_query_var('term') : false;

    $arqs = array(
            'post_type' => 'person',
            'subgroup' => $group,
            'posts_per_page' => 20,
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
    $wp_query = new WP_Query($arqs);
    $number_of_pages = $wp_query->max_num_pages;

?>

<?php  get_header(); ?>

<body>
<?php include get_template_directory() . '/masthead.php'; ?>

<div id="main-content" role="main">

<!-- Remove for custom index -->
<section class="text-mod no-components" id="directory-index">
  <div class="container">
    <div class="section-txt">
	<h1 class="section-head"><?php echo (single_cat_title('',false)) ? single_cat_title('', false) : 'Directory';?></h1>
        <div class="row">
    	<?php 
            if ( $wp_query->have_posts() ) :
                while( $wp_query->have_posts() ) {
                    $wp_query->the_post();
                    $person = get_post();
                    echo print_person($person);
                }

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
                        foreach($links as $link) {
                            $class = (strpos($link,'current')) ? ' class="active"' : '';
                            echo '<li' . $class . '>' . $link . '</li>';
                        }
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
