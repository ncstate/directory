<?php 

// 1. customize ACF path
add_filter('acf/settings/path', 'ncstate_directory_acf_settings_path');
 
function ncstate_directory_acf_settings_path( $path ) {
 
    // update path
    $path = plugin_dir_path(__FILE__) . 'acf/';
    
    // return
    return $path;
    
}
 

// 2. customize ACF dir
add_filter('acf/settings/dir', 'ncstate_directory_acf_settings_dir');
 
function ncstate_directory_acf_settings_dir( $dir ) {
 
    // update path
    $dir = plugin_dir_url(__FILE__) . 'acf/';
    
    // return
    return $dir;
    
}
 

// 3. Hide ACF field group menu item
//add_filter('acf/settings/show_admin', '__return_false');

// 4. Include ACF
include_once( plugin_dir_path(__FILE__) . 'acf/acf.php' );