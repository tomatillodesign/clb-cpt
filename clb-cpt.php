<?php
/*
Plugin Name: Custom Post Types & Added Functionality
Description: Site-specific code changes for CFHA Technical Assistance
Author: Tomatillo Design
Author URI: http://www.tomatillodesign.com
Version: 1.1
*/


/* Start Adding Functions Below this Line */


//Create Custom Post Types
add_action( 'init', 'clb_add_posts' );
function clb_add_posts() {

//  clb_create_post_types($singular, $plural, $slug, $dashicon = 'admin-post', $supports = array( 'title', 'editor', 'thumbnail', 'genesis-cpt-archives-settings', 'page-attributes', 'excerpt', 'author' ))
//  clb_create_post_types('Book', 'Books', 'book', 'controls-repeat', array('title', 'editor'));

    clb_create_post_types('Conference', 'Conferences', 'conferences', 'admin-site');
    clb_create_post_types('Workshop', 'Workshops', 'workshops', 'nametag');
    clb_create_post_types('Speaker', 'Speakers', 'speakers', 'businesswoman');
    clb_create_post_types('Sponsor', 'Sponsors', 'sponsors', 'awards');

}


//Create Custom Taxonomies
add_action( 'init', 'clb_add_taxonomies' );
function clb_add_taxonomies() {

//  clb_create_taxonomies($singular, $plural, $slug, $hierarchical = true, $post_types = array())
//  clb_create_taxonomies('Genre', 'Genres', 'genre', true, array( 'movie', 'post' ));

     clb_create_taxonomies('Year', 'Years', 'conference_years', true, array( 'conferences', 'workshops', 'sponsors' ));
     clb_create_taxonomies('Workshop Category', 'Workshop Categories', 'workshop_categories', true, array( 'workshops' ));
     clb_create_taxonomies('Workshop Keyword', 'Workshop Keywords', 'workshop_keywords', false, array( 'workshops' ));
     clb_create_taxonomies('Workshop Level', 'Workshop Levels', 'workshop_levels', true, array( 'workshops' ));
     clb_create_taxonomies('Sponsor Category', 'Sponsor Categories', 'sponsor_categories', true, array( 'sponsors' ));

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







function clb_change_tax_object_label_tags() {
    global $wp_taxonomies;
    $labels = &$wp_taxonomies['post_tag']->labels;
    $labels->name = "Topic";
    $labels->singular_name = "Topics";
    $labels->search_items = "Search topics";
    $labels->all_items = "Topics";
    $labels->separate_items_with_commas = "Separate topics with commas";
    $labels->choose_from_most_used = "Choose from most used topics";
    $labels->popular_items = "Popular topics";
    $labels->edit_item = "Edit topic";
    $labels->view_item = "View topic";
    $labels->update_item = "Update topic";
    $labels->add_new_item = "Add new topic";
    $labels->new_item_name = "New topic";
    $labels->add_or_remove_items = "Add or remove topics";
    $labels->not_found = "Topic not found";
    $labels->no_terms = "No topics";
    $labels->items_list_navigation = "Navigation list of topics";
    $labels->items_list = "Topic list";
    $labels->back_to_items = "Back to topics";
    $labels->menu_name = "Topics";
}
add_action( 'init', 'clb_change_tax_object_label_tags' );

// function clb_reg_tag_to_cpts() {
//      register_taxonomy_for_object_type('post_tag', 'resources');
//      register_taxonomy_for_object_type('post_tag', 'consultants');
// }
// add_action('init', 'clb_reg_tag_to_cpts');




add_image_size( 'large-square', 800, 800, true );




// Enqueue script - open all links of other sites in new tab
function clb_enqueue_set_link_target_scripts() {
     wp_enqueue_script( 'set-link-targets', plugin_dir_url( __FILE__ ) . 'js/set-link-targets.js', array( 'jquery' ), '', true );
}
add_action( 'wp_enqueue_scripts', 'clb_enqueue_set_link_target_scripts' );



// Custom Conference Info
// Let's start adding automatic conferenc info

// Setup transients and custom functions to get out the pieces of the data as needed



// Add Shortcode
function clb_current_schedule_menu_item() {

     // Get current conference info
     $conference_info = get_current_conference_info();
     $conference_ID = $conference_info['ID'];
     $conference_title = $conference_info['title'];
     $conference_tagline = $conference_info['tagline'];
     $conference_summary = $conference_info['summary'];
     $conference_start_date = $conference_info['start_date'];
     $conference_end_date = $conference_info['end_date'];
     $conference_venue = $conference_info['venue'];
     $conference_street_address = $conference_info['street_address'];
     $conference_city = $conference_info['city'];
     $conference_state = $conference_info['state'];
     $conference_zip = $conference_info['zip'];
     $conference_registration_url = $conference_info['registration_url'];
     $conference_schedule = $conference_info['schedule_url'];

     return '<a href="' . $conference_schedule . '" target="_blank">Schedule</a>';

}
add_shortcode( 'main_menu_schedule', 'clb_current_schedule_menu_item' );




// Add Shortcode
function clb_footer_upcoming_conference_info() {

     // Get current conference info
     $conference_info = get_current_conference_info();
     $conference_ID = $conference_info['ID'];
     $conference_title = $conference_info['title'];
     $conference_tagline = $conference_info['tagline'];
     $conference_summary = $conference_info['summary'];
     $conference_start_date = $conference_info['start_date'];
     $conference_end_date = $conference_info['end_date'];
     $conference_venue = $conference_info['venue'];
     $conference_street_address = $conference_info['street_address'];
     $conference_city = $conference_info['city'];
     $conference_state = $conference_info['state'];
     $conference_zip = $conference_info['zip'];
     $conference_registration_url = $conference_info['registration_url'];
     $conference_schedule = $conference_info['schedule_url'];

          // Calculate the countdown
          $earlier = new DateTime();
          $later = new DateTime($conference_start_date);
          $diff = $later->diff($earlier)->format("%a") + 1;

               if( $diff > 1 ) { $days = $diff . ' days until the conference!'; }
               elseif( $diff == 1 ) { $days = $diff . ' day until the conference!'; }
               elseif( $diff < 1 && $diff > -3 ) { $days = 'We are in session now!'; }
               elseif( $diff <= -3 ) { $days = 'We just wrapped up our conference for this year.'; }


          //Format date display
          $conference_start_date_abbrev = date("M j", strtotime($conference_start_date));
          $conference_end_date_abbrev = date("M j", strtotime($conference_end_date));
          $conference_year = date("Y", strtotime($conference_start_date));
          $conference_pub_date = $conference_start_date_abbrev . '-' . $conference_end_date_abbrev . ' ' . $conference_year;

     return '
     <div class="conference-single-line-info">' . $days . '</div>
     <div class="conference-single-line-info">' . $conference_pub_date . '</div>
     <div class="conference-single-line-info">' . $conference_venue . '</div>
     <div class="conference-single-line-info">' . $conference_street_address . '</div>
     <div class="conference-single-line-info">' . $conference_city . ', ' . $conference_state . ' ' . $conference_zip . '</div>
     <div class="conference-single-line-info"><a href="' . $conference_schedule . '" target="_blank">View Schedule</a></div>
     <div class="conference-single-line-info"><a href="' . $conference_registration_url . '" target="_blank" class="button">Register Now</a></div>';

}
add_shortcode( 'footer_upcoming_conference_info', 'clb_footer_upcoming_conference_info' );



add_action('genesis_before_content_sidebar_wrap', 'clb_publish_conference_date_summary');
function clb_publish_conference_date_summary() {

     $select_current_conference = get_field('select_current_conference', 'option');
     if( $select_current_conference ) {

          // Get current conference info
          $conference_info = get_current_conference_info();
          $conference_ID = $conference_info['ID'];
          $conference_title = $conference_info['title'];
          $conference_tagline = $conference_info['tagline'];
          $conference_summary = $conference_info['summary'];

          echo '<div class="conference-date-summary"><i class="fas fa-hand-point-right fa-lg"></i> ' . $conference_summary . '</div>';

     }

}




// Get the list of workshop speakers and return names as array
function clb_get_workshop_speakers( $workshop_ID, $linked = false ) {

     $workshop_speaker_array = array();

     if( !$linked ) {

          $workshop_1_lead_presenter = get_field('workshop_1_lead_presenter', $workshop_ID);
          if( $workshop_1_lead_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_1_lead_presenter ); }

          $workshop_2_presenter = get_field('workshop_2_presenter', $workshop_ID);
          if( $workshop_2_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_2_presenter ); }

          $workshop_3_presenter = get_field('workshop_3_presenter', $workshop_ID);
          if( $workshop_3_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_3_presenter ); }

          $workshop_4_presenter = get_field('workshop_4_presenter', $workshop_ID);
          if( $workshop_4_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_4_presenter ); }

          $workshop_5_presenter = get_field('workshop_5_presenter', $workshop_ID);
          if( $workshop_5_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_5_presenter ); }

          $workshop_6_presenter = get_field('workshop_6_presenter', $workshop_ID);
          if( $workshop_6_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_6_presenter ); }

          $workshop_7_presenter = get_field('workshop_7_presenter', $workshop_ID);
          if( $workshop_7_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_7_presenter ); }

          $workshop_8_presenter = get_field('workshop_8_presenter', $workshop_ID);
          if( $workshop_8_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_8_presenter ); }

          $workshop_9_presenter = get_field('workshop_9_presenter', $workshop_ID);
          if( $workshop_9_presenter ) { $workshop_speaker_array[] = get_the_title( $workshop_9_presenter ); }

     } elseif( $linked ) {

          $workshop_1_lead_presenter = get_field('workshop_1_lead_presenter', $workshop_ID);
          if( $workshop_1_lead_presenter ) {
               $permalink = get_the_permalink( $workshop_1_lead_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_1_lead_presenter ) . '</a>';
          }

          $workshop_2_presenter = get_field('workshop_2_presenter', $workshop_ID);
          if( $workshop_2_presenter ) {
               $permalink = get_the_permalink( $workshop_2_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_2_presenter ) . '</a>';
          }

          $workshop_3_presenter = get_field('workshop_3_presenter', $workshop_ID);
          if( $workshop_3_presenter ) {
               $permalink = get_the_permalink( $workshop_3_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_3_presenter ) . '</a>';
          }

          $workshop_4_presenter = get_field('workshop_4_presenter', $workshop_ID);
          if( $workshop_4_presenter ) {
               $permalink = get_the_permalink( $workshop_4_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_4_presenter ) . '</a>';
          }

          $workshop_5_presenter = get_field('workshop_5_presenter', $workshop_ID);
          if( $workshop_5_presenter ) {
               $permalink = get_the_permalink( $workshop_5_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_5_presenter ) . '</a>';
          }

          $workshop_6_presenter = get_field('workshop_6_presenter', $workshop_ID);
          if( $workshop_6_presenter ) {
               $permalink = get_the_permalink( $workshop_6_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_6_presenter ) . '</a>';
          }

          $workshop_7_presenter = get_field('workshop_7_presenter', $workshop_ID);
          if( $workshop_7_presenter ) {
               $permalink = get_the_permalink( $workshop_7_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_7_presenter ) . '</a>';
          }

          $workshop_8_presenter = get_field('workshop_8_presenter', $workshop_ID);
          if( $workshop_8_presenter ) {
               $permalink = get_the_permalink( $workshop_8_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_8_presenter ) . '</a>';
          }

          $workshop_9_presenter = get_field('workshop_9_presenter', $workshop_ID);
          if( $workshop_9_presenter ) {
               $permalink = get_the_permalink( $workshop_9_presenter );
               $workshop_speaker_array[] = '<a href="' . $permalink . '">' . get_the_title( $workshop_9_presenter ) . '</a>';
          }
     }

     return $workshop_speaker_array;

}





// Get the current list of workshop speakers and return IDS as array
function clb_get_workshop_speaker_ids( $workshop_ID ) {

     $workshop_speaker_array = array();

          $workshop_1_lead_presenter = get_field('workshop_1_lead_presenter', $workshop_ID);
          if( $workshop_1_lead_presenter ) { $workshop_speaker_array[] = $workshop_1_lead_presenter; }

          $workshop_2_presenter = get_field('workshop_2_presenter', $workshop_ID);
          if( $workshop_2_presenter ) { $workshop_speaker_array[] = $workshop_2_presenter; }

          $workshop_3_presenter = get_field('workshop_3_presenter', $workshop_ID);
          if( $workshop_3_presenter ) { $workshop_speaker_array[] = $workshop_3_presenter; }

          $workshop_4_presenter = get_field('workshop_4_presenter', $workshop_ID);
          if( $workshop_4_presenter ) { $workshop_speaker_array[] = $workshop_4_presenter; }

          $workshop_5_presenter = get_field('workshop_5_presenter', $workshop_ID);
          if( $workshop_5_presenter ) { $workshop_speaker_array[] = $workshop_5_presenter; }

          $workshop_6_presenter = get_field('workshop_6_presenter', $workshop_ID);
          if( $workshop_6_presenter ) { $workshop_speaker_array[] = $workshop_6_presenter; }

          $workshop_7_presenter = get_field('workshop_7_presenter', $workshop_ID);
          if( $workshop_7_presenter ) { $workshop_speaker_array[] = $workshop_7_presenter; }

          $workshop_8_presenter = get_field('workshop_8_presenter', $workshop_ID);
          if( $workshop_8_presenter ) { $workshop_speaker_array[] = $workshop_8_presenter; }

          $workshop_9_presenter = get_field('workshop_9_presenter', $workshop_ID);
          if( $workshop_9_presenter ) { $workshop_speaker_array[] = $workshop_9_presenter; }

     return $workshop_speaker_array;


}


// Add Shortcode
function clb_homepage_content() {

     $post_id = get_option('page_on_front'); // example post id
     $post_content = get_post($post_id);
     $content = $post_content->post_content;

     return apply_filters('the_content', $content);

}
add_shortcode( 'homepage_content', 'clb_homepage_content' );


// Run Loops and Code --> DEV PURPOSES ONLY
add_action('genesis_entry_content', 'clb_dev_update_posts');
function clb_dev_update_posts() {

     if( is_page( '1053' ) ) {


          print_r( get_current_speaker_info() );



          // // Sample WP Query
          // $args = array(
          //      'post_type' => 'workshops', // enter your custom post type
          //      'orderby' => 'date',
          //      'order' => 'ASC',
          //      'post_status' => 'publish',
          //      'posts_per_page'=> -1,
          // );
          //
          // // The Query
          // $the_query = new WP_Query( $args );
          //
          // // The Loop
          // if ( $the_query->have_posts() ) {
          //
          //           while ( $the_query->have_posts() ) {
          //
          //           $the_query->the_post();
          //
          //           $counter++;
          //
          //           //vars
          //           $workshop_id = get_the_ID();
          //           $title = get_the_title();
          //           $date = get_the_date();
          //           $permalink = get_the_permalink();
          //
          //           // Get all the workshop leaders into a single string variable
          //           $workshop_speakers_array = clb_get_workshop_speakers( $workshop_id );
          // 		$presenters_to_publish = null;
          //
          //           foreach( $workshop_speakers_array as $single_speaker ) {
          //                $presenters_to_publish .= $single_speaker . ', ';
          //           }
          //
          //           //Do something here
          //           $update = update_field('workshop_additional_search_terms', $presenters_to_publish);
          //           if($update) {
          //                echo '<div>Updated ' . $title . '</div>';
          //           } else {
          //                echo '<div>NOT Updated ' . $title . '</div>';
          //           }
          //
          //      }
          //
          // }
          //
          // wp_reset_postdata();
          //
          //


     }


}
