<?php

/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/
 
if ( ! defined( 'NCSTATE_DIRECTORY_BASE_FILE' ) )
    define( 'NCSTATE_DIRECTORY_BASE_FILE', __FILE__ );
if ( ! defined( 'NCSTATE_DIRECTORY_BASE_DIR' ) )
    define( 'NCSTATE_DIRECTORY_BASE_DIR', dirname( NCSTATE_DIRECTORY_BASE_FILE ) );
if ( ! defined( 'NCSTATE_DIRECTORY_PLUGIN_URL' ) )
    define( 'NCSTATE_DIRECTORY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Get the custom template if is set
 *
 * @since 1.0
 */
 
function ncstate_directory_get_template_hierarchy( $template ) {
 
    // Get the template slug
    $template_slug = rtrim( $template, '.php' );
    $template = $template_slug . '.php';
 
    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    if ( $theme_file = locate_template( array( 'plugin_template/' . $template ) ) ) {
        $file = $theme_file;
    }
    else {
        $file = NCSTATE_DIRECTORY_BASE_DIR . '/views/' . $template;
    }
 
    return apply_filters( 'ncstate_directory_repl_template_' . $template, $file );
}

/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/
 
add_filter( 'template_include', 'ncstate_directory_template_chooser');
 
/*
|--------------------------------------------------------------------------
| PLUGIN FUNCTIONS
|--------------------------------------------------------------------------
*/
 
/**
 * Returns template file
 *
 * @since 1.0
 */
 
function ncstate_directory_template_chooser( $template ) {
 
    $post_id = get_the_ID();
    if ( get_post_type( $post_id ) == 'person' ) {
        return ncstate_directory_get_template_hierarchy( 'single' );
    }
 
 	return $template;
 
}