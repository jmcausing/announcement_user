<?php
	
/*
Plugin Name: Announcement User
Plugin URI: http://johnmark.me/
Date; November 11, 2021
Description: This plugin is using shortcode method to display announcemet to all users and for specific user
Author: John Mark Causing
Author URI:  http://johnmark.me/
*/

// Include the ACF plugin.
include_once( 'includes/acf/acf.php' );

// PHP codes from ACF for custom field 'target_user'
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_618ae2ff93c5d',
		'title' => 'Announcements Private specific user',
		'fields' => array(
			array(
				'key' => 'field_618ae31e27994',
				'label' => 'Target User',
				'name' => 'target_user',
				'type' => 'user',
				'instructions' => 'Select a specific user/users that you want this announcement to show up.',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'role' => '',
				'allow_null' => 0,
				'multiple' => 1,
				'return_format' => 'object',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'announcement_private',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
	endif;		



/*
START
This is for Announcements Global custom post type
*/

function cptui_register_my_cpts_announcement_global() {
	/**
	 * Post Type: Announcements Global.
	 */

	$labels = [
		"name" => __( "Announcements Global", "custom-post-type-ui" ),
		"singular_name" => __( "Announcement Global", "custom-post-type-ui" ),
	];

	$args = [
		"label" => __( "Announcements Global", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "Announcement Global is where admin can post announcements for subscribers.",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "announcement_global", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "custom-fields" ],
		"show_in_graphql" => false,
	];

	register_post_type( "announcement_global", $args );
}

add_action( 'init', 'cptui_register_my_cpts_announcement_global' );



// START -- Generate shortcodes for each blog post layout
// #####

// Load ann_add_shortcode() function after WP loads.
add_action( 'init', 'ann_add_shortcode' );

function ann_add_shortcode() {
 	add_shortcode('announcement', 'ann_shortcode');  // check also if the shortcode string is the same in the admin column
}

function ann_shortcode( $attr, $content="") {
	ob_start();	

	// The Query
	$the_query = new WP_Query( 
		array(
			'post_type' => 'announcement_global',
			// 'posts_per_page' => 2
		) 
	);


	$output = '<div class="announcement_global_container">';


	// The Loop
	if ( $the_query->have_posts() ) {

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$output .= '
			<h2>' . get_the_title() . '</h2>
			<p>' . get_the_date() . '</p>
			<div>' . get_the_content() . '</div><hr>';

		}

	$output .= '</div>';

	echo  $output;		

	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();

    return ob_get_clean();

}

// #####
// END -- Generate shortcodes for each blog post layout

/*
END
This is for Announcements Global custom post type
*/



/*
START
This is for Announcements Private custom post type
*/

function cptui_register_my_cpts_announcement_private() {
	/**
	 * Post Type: Announcements Private.
	 */

	$labels = [
		"name" => __( "Announcements Private", "custom-post-type-ui" ),
		"singular_name" => __( "Announcement Private", "custom-post-type-ui" ),
	];

	$args = [
		"label" => __( "Announcements Private", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "Announcement Private is where admin can post announcements for subscribers.",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "announcement_private", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "custom-fields" ],
		"show_in_graphql" => false,
	];

	register_post_type( "announcement_private", $args );
}

add_action( 'init', 'cptui_register_my_cpts_announcement_private' );



// START -- Generate shortcodes for each blog post layout
// #####

// Load ann_add_shortcode() function after WP loads.
add_action( 'init', 'annpriv_add_shortcode' );

function annpriv_add_shortcode() {
 	add_shortcode('announcement_private', 'annpriv_shortcode');  // check also if the shortcode string is the same in the admin column
}

function annpriv_shortcode( $attr, $content="") {
	ob_start();	

    $args = array(  
        'post_type' => 'announcement_private',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => 'title', 
        'order' => 'ASC', 
    );

    $loop = new WP_Query( $args ); 


	// Get all post from CPT 'announcement_private'
    while ( $loop->have_posts() ) : $loop->the_post(); 

		// Set the output var and class
		$output = '<div class="announcement_container">';
    

        $title = 'Title: ' . get_the_title(). '<br>';
		$content = get_the_content();
		$content = wpautop($content);
		$date =  get_the_date();

		// Do the loop game here!
		$post_id = get_the_ID();

		// Get value of custom field 'target_user' - ACF setting is set as Object Relational
		$target_users = get_field('target_user', $post_id);

		foreach($target_users as $target_user):

			$target_user_value = $target_user->user_login;

			// Echo for debugging
			// echo "TARGET USER: $target_user_value <br>";

			// Get current user login
			global $current_user; wp_get_current_user(); 
			$current_user_login = $current_user->user_login;
				
			// var_dump($target_users);
			// If target user matched the current user
			if ($target_user_value==$current_user_login) {
				// Echo for debugging
				//	echo "$target_user_value and $current_user_login are the same";


				// echo  ' I am ' . $current_user_login . 'and listed here! Title of this announcement is: ' . $title . '<br>';
				$output_shortcode =  '<style>.wp-admin .announcement_container { display: none; }</style><div class="announcement_container"><h2>' . $title . '</h2><p>' . $date . '</p><div class="ann_content">' . $content . '</div><hr><br></div>';
				print $output_shortcode;
			}
			// 
			// Comment this out. This will echo if more users are added.
			else {
				$output .= ' YOU ARE NOT LISTED HERE! Looking for ' . $target_user_value . ' - Title: ' . get_the_title() . '<br>';
			}
			// 
			// End loop of the custome field array	

		endforeach;
			
	//$output .= '</div>';
	//return $output;	

	  
	// End loop   
    endwhile;

	// Restore original Post Data 
    wp_reset_postdata(); 

	return ob_get_clean();
		
}

// #####
// END -- Generate shortcodes for each blog post layout

/*
END
This is for Announcements Private custom post type
*/































