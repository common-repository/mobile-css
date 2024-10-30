<?php

	// register post type
	
	
	// creates a new post_type in WordPress called Campaigns
	function MC_create_post_types() {
	
		$args = array(
			'labels' 		=> array(
				'name' 				=> __( 'CSS Rules' ),
				'add_new' 			=> __( 'Create New' ),
				'singular_name' 	=> __( 'Rule' ),
				'new_item' 			=> __( 'New Rule' ),
				'add_new_item' 		=> __( 'Add New Rule' ),
				'edit_item' 		=> __( 'Edit Rule' ),
				'view_item' 		=> __( 'View Rule' ),
				'search_items' 		=> __( 'Search Rules' ),
				'not_found' 		=> __( 'No rules found.' ),
				'not_found_in_trash' => __( 'No rules found in trash.' )
				),
			'capability_type' 		=> 'mc_rule',
			'capabilities' 			=> array(
				'publish_posts' 		=> 'manage_options',
				'edit_posts' 			=> 'manage_options',
				'edit_others_posts' 	=> 'manage_options',
				'delete_posts' 			=> 'manage_options',
				'delete_others_posts' 	=> 'manage_options',
				'read_private_posts' 	=> 'manage_options',
				'edit_post' 			=> 'manage_options',
				'delete_post' 			=> 'manage_options',
				'read_post' 			=> 'manage_options',
			),
			'description' 			=> 'Mobile CSS Rules.',
			'public' 				=> true,
			'has_archive' 			=> false,
			'rewrite' 				=> false,
			'exclude_from_search' 	=> true,
			'hierarchical' 			=> false,
			'supports' 				=> array(
				"title",
				"editor"
				)
			);
		
		register_post_type( 'mc_rule', $args );
		
	}
	
	add_action( "init", "MC_create_post_types" );
	
?>