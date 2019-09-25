<?php
/*
Plugin Name: Everything Bagel 😀
Description: Site-specific functionality and custom code for SITE_NAME_HERE
Author: Chris Liu-Beers, Tomatillo Design
Author URI: http://www.tomatillodesign.com
Version: 1.1
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/


/* Start Adding Functions Below this Line */


//Create Custom Post Types
add_action( 'init', 'clb_add_posts' );
function clb_add_posts() {

//  clb_create_post_types($singular, $plural, $slug, $dashicon = 'admin-post', $supports = array( 'title', 'editor', 'thumbnail', 'genesis-cpt-archives-settings', 'page-attributes', 'excerpt', 'author' ))
//  clb_create_post_types('Book', 'Books', 'book', 'controls-repeat', array('title', 'editor'));



}


//Create Custom Taxonomies
add_action( 'init', 'clb_add_taxonomies' );
function clb_add_taxonomies() {

//  clb_create_taxonomies($singular, $plural, $slug, $hierarchical = true, $post_types = array())
//  clb_create_taxonomies('Genre', 'Genres', 'genre', true, array( 'movie', 'post' ));


}


//Loops through and creates the post types
function clb_create_post_types($singular, $plural, $slug, $dashicon = 'admin-post', $supports = array( 'title', 'editor', 'thumbnail', 'genesis-cpt-archives-settings', 'page-attributes', 'author', 'revisions', 'custom-fields', 'excerpt' )) {

    register_post_type( $slug,
        array(
            'labels' => array(
                'name' => __( $plural ),
                'singular_name' => __( $singular ),
                'add_new' => _x('Add new ' . $singular, $plural),
                'add_new_item' => __('Add new ' . $singular),
                'edit_item' => __('Edit ' . $singular),
                'new_item' => __('New ' . $singular),
                'view_item' => __('View ' . $singular),
                'all_items' => __( 'All ' . $plural),
                'search_items' => __( 'Search ' . $plural),
                'not_found' => __( 'No ' . $plural . ' found.' ),
            ),
            'has_archive' => false, // by default, usually set to TRUE but I wanted to use the page slugs, '/speakers', '/workshops' etc. and didn't really need public archives anyway
            'public' => true,
            'menu_icon' => 'dashicons-' . $dashicon, // see full list of dashicons here: http://www.kevinleary.net/dashicons-custom-post-type/
            'show_ui' => true, // defaults to true so don't have to include
            'show_in_menu' => true, // defaults to true so don't have to include
            'menu_position' => 20, // set default position in left-hand WP menu
            'rewrite' => array( 'slug' => $slug ),
            'supports' => $supports,
            'show_in_rest' => true,
            'rest_base'          => $slug,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'capability_type'    => 'post',
        )
    );

}

//Loops through and creates the custom taxonomies
function clb_create_taxonomies($singular, $plural, $slug, $hierarchical = true, $post_types = array('post')) {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( $plural ),
    'singular_name'     => __( $singular ),
    'search_items'      => __( 'Search ' . $plural ),
    'all_items'         => __( 'All ' . $plural ),
    'parent_item'       => __( 'Parent ' . $plural ),
    'parent_item_colon' => __( 'Parent ' . $singular . ':' ),
    'edit_item'         => __( 'Edit ' . $plural ),
    'update_item'       => __( 'Update ' . $singular ),
    'add_new_item'      => __( 'Add New ' . $singular ),
    'new_item_name'     => __( 'New ' . $singular ),
    'menu_name'         => __( $plural ),
    'not_found'         => __( 'No ' . $plural . ' found.' ),
  );

  $args = array(
    'hierarchical'      => $hierarchical,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => $slug ),
    'show_in_rest'          => true,
    'rest_base'             => $slug,
    'rest_controller_class' => 'WP_REST_Terms_Controller',
  );

  register_taxonomy( $slug, $post_types, $args );

}


//--------- CLB Standard Site Customizations



//Add a new custom widget to the WordPress Dashboard
function clb_register_my_dashboard_widget_info_for_client() {
    global $wp_meta_boxes;

    $site_title = get_bloginfo();
    $welcome = 'Welcome to ' . $site_title;

    wp_add_dashboard_widget(
        'my_dashboard_widget',
        $welcome,
        'clb_my_dashboard_widget_display_text'
    );

    $dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

    $my_widget = array( 'my_dashboard_widget' => $dashboard['my_dashboard_widget'] );
    unset( $dashboard['my_dashboard_widget'] );

    $sorted_dashboard = array_merge( $my_widget, $dashboard );
    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action( 'wp_dashboard_setup', 'clb_register_my_dashboard_widget_info_for_client' );

function clb_my_dashboard_widget_display_text() {
    ?>

    <p>Congratulations on launching your new website.</p>
    <p>Please don't hesitate to contact me if you have any questions:<br/>
<a href="http://www.tomatillodesign.com" title="Amazing, Affordable Websites for Nonprofits" target="_blank"><img src="//www.tomatillodesign.com/wp-content/uploads/2011/03/tomatillo_only_190.jpg" style="float:right"></a><a href="mailto:chris@tomatillodesign.com" target="_blank">chris@tomatillodesign.com</a> | 919.576.0180</p>

<p>Thanks for choosing to work with <a href="http://www.tomatillodesign.com" title="Amazing, Affordable Websites for Nonprofits" target="_blank">Tomatillo Design</a>.</p>

    <?php
}




/////////////////////////////
// Add new image sizes to WP
// add_image_size( 'large-square', 800, 800, true );
/////////////////////////////




// Enqueue script - open all links of other sites in new tab
add_action( 'wp_enqueue_scripts', 'clb_enqueue_set_link_target_scripts' );
function clb_enqueue_set_link_target_scripts() {

     wp_enqueue_script( 'set-link-targets', plugin_dir_url( __FILE__ ) . 'js/set-link-targets.js', array( 'jquery' ), '', true );

}





//* Change the footer text
// Note, this is now no longer the preferred method in Genesis, see:
// However, the customizer does not allow for dynamic PHP
// Might be better to still start here

// add_filter('genesis_footer_creds_text', 'clb_footer_creds_filter');
// function clb_footer_creds_filter( $creds ) {
//
//      $blog_title = get_bloginfo();
//
//     $creds = '<div class="footer-credit-area">Copyright [footer_copyright] ' . $blog_title . ' &middot; All Rights Reserved &middot; Website by <a href="http://www.tomatillodesign.com/" title="Amazing, Affordable Websites for Nonprofits" target="_blank">Tomatillo Design</a></div>';
//     return $creds;
//
// }




// OPTIONAL
// Defines Clickable Logo and adds Blog Info to title
// function special_site_logo() {
//
//      echo '<a id="sitelogo" href="' . bloginfo( 'url' ) . '"><img src="/wp-content/uploads/2019/07/logo-only.png" alt="' . bloginfo('name') . '" /></a>';
// 
// }
// add_action( 'genesis_site_title','special_site_logo',5,1);
