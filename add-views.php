<?php

add_filter('single_template', 'person_get_single');
function person_get_single($single) {
    $is_person = get_post_type($post_id) == 'person' ? true : false;

    if ( file_exists(get_stylesheet_directory() . '/ncstate-directory/views/single.php') 
         && $is_person ) :
        return get_stylesheet_directory() . '/ncstate-directory/views/single.php';
    elseif ($is_person):
        return plugin_dir_path(__FILE__) . 'views/single.php';
    else:
        return $single;
    endif;
}

// add_filter('index_template', 'events_get_index');
// function events_get_index($index) {
//     $post_type = get_query_var( 'post_type' ); 

//     if($post_type == 'events'):
//         return plugin_dir_path(__FILE__) . 'views/index.php';
//     else:
//         return $index;
//     endif;
// }
