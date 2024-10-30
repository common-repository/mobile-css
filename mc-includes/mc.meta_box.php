<?php


	// add box to mc_rule post type for selecting devices
	function MC_add_custom_box() {
	
		// define meta box
		add_meta_box( 
			'MC_mobile_rules',
			'Mobile CSS Rules',
			'MC_mobile_rules',
			'mc_rule',
			'normal',
			'high'
		);
	}
	
	// add out action
	add_action( 'add_meta_boxes', 'MC_add_custom_box' );





	// display our options
	function MC_mobile_rules( $post ) {

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'MC_noncename' );
		
		$options = array();
		
		// The actual fields for data entry
		if ( function_exists( 'MC_rule_options' ) ) {
			$options = MC_rule_options();
		} else {
			echo 'Cannot find function: MC_rule_options';
		}
		
		$current_values = get_post_meta( $post->ID, 'mc_rules' );

		if ( is_serialized( $current_values[0] ) ) {
			$current_values = unserialize( $current_values[0] );
		} else {
			$current_values = $current_values[0];
		}
		
		MC_options_form( $options, $current_values );
	}



	// when post is saved, run this
	function MC_save_rulesdata( $post_id ) {
	
		// verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['MC_noncename'], plugin_basename( __FILE__ ) ) ) {
			return;
		}
		
		
		// Check permissions
		if ( 'mc_rule' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				// return;
			}
		}

		// OK, we're authenticated: we need to find and save the data
		
		$values = array();
		
		$options = MC_rule_options();
		
		foreach ( $options as $option ) {
			if ( !empty( $_POST[$option['id']] ) ) {
				$values[$option['id']] = $_POST[$option['id']];
			}
		}
		
		update_post_meta( $post_id, 'mc_rules', $values );
	}
	
	/* Do something with the data entered */
	add_action( 'save_post', 'MC_save_rulesdata' );

?>