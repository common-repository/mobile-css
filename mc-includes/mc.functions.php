<?php

	// custom functions
	
	if ( !function_exists( 'MC_head' ) ) {
	
		function MC_head() {
		
			if ( class_exists( 'Mobile_Detect' ) ) {
			
				$detect = new Mobile_Detect();
				
				if ( $detect->isMobile() ) {
				
					$output = '';
					
					$args = array(
						'post_type'		=> 'mc_rule',
						'post_status'	=> 'publish',
						'numberposts'	=> -1
						);
					
					$rules = get_posts( $args );
					
					foreach ( $rules as $rule ) {
					
						$show = false;
						
						// get rule data
						$current_values = get_post_meta( $rule->ID, 'mc_rules' );
						if ( is_serialized( $current_values[0] ) ) {
							$current_values = unserialize( $current_values[0] );
						} else {
							$current_values = $current_values[0];
						}
						
						if ( $current_values['mc_device_type'] == 'phone' ) {
						
							if ( !$detect->istablet() ) {
								
								if ( $current_values['mc_phone_type'] == 'all' ) {
								
									$show = true;
								
								} else {
									
									$show = call_user_func( array( &$detect, 'is' . $current_values['mc_phone_type'] ) );
								}
							}
						
						} elseif ( $current_values['mc_device_type'] == 'tablet' ) {
						
							if ( $detect->istablet() ) {
							
								if ( $current_values['mc_tablet_type'] == 'all' ) {
								
									$show = true;
								
								} else {
								
									$show = call_user_func( array( &$detect, 'is' . $current_values['mc_tablet_type'] ) );
								}
							}
						
						} else {
						
							$show = true;
						}
						
						if ( $show === false ) {
							continue;
						}
						
						$show = apply_filters( 'MC_head_check', $show, $current_values, $detect );
						
						if ( $show === false ) {
							continue;
						}
						
						$output .= '
							/* START: ' . $rule->post_name . ' */
							';
						$output .= $rule->post_content;
						$output .= '
							/* END: ' . $rule->post_name . ' */
							';
					}
					
					if ( !empty( $output ) ) {
					
						$output = apply_filters( 'MC_css_output', $output );
						
						echo '<!-- Mobile CSS styles - ' . MOBILECSSISPRO . ' -->';
						
						do_action( 'MC_before_script', $current_values, $detect );
						
						echo '<style type="text/css">';
						
							do_action( 'MC_inside_script', $current_values, $detect );
							
							echo $output;
							
						echo '</style>';
						
						do_action( 'MC_after_script', $current_values, $detect );
					}
				}
			}
		}
		
		add_action( 'wp_head', 'MC_head' );
	}
	
	
	
	// change default title to instruction
	function MC_change_default_title( $title, $post ) {
	
		// if this is a mc_rule post type
		if ( $post->post_type == 'mc_rule' ) {
		
			// our new title
			$title = 'Enter a short description for this rule here.';
		}
		
		// return title
		return $title;
	}
	
	// add filter
	// apply_filters( 'enter_title_here', __( 'Enter title here' ), $post );
	add_filter( 'enter_title_here', 'MC_change_default_title', 1, 2 );
	
	
	// disable visual editor for CSS rules
	function MC_disable_tinymce( $default ) {
	
		// get post
		global $post;
		
		if ( 'mc_rule' == $post->post_type ) {
			return false;
		}
		
		return $default;
	}
	
	// add filter
	add_filter( 'user_can_richedit', 'MC_disable_tinymce' );
	
	
	// add CSs styles to the admin ehad
	function MC_styles() {
	
		// get post
		global $post;
		
		// if we're viewing mc_rule post type
		if ( 'mc_rule' == $post->post_type ) {
		
			// echo CSS styles
			// hide editor buttons
			// offset title
			// hide media buttons
			echo '
				<style type="text/css">
				
					#ed_toolbar input,
					#edit-slug-box,
					#wp-content-media-buttons a {
						display: none;
					}
					#wp-content-media-buttons h3 {
						margin-top: 23px;
					}
					
					.mc_form_field {
						margin: 0px 0px 10px 0px;
						
						border-bottom: 1px solid #ddd;
						padding: 10px 10px;
						text-align: left;
						vertical-align: top;
					}
					
					.mc_input_container {
						display: block;
						margin-left: 200px;
					}
					
					.mc_form_field label {
						display: block;
						float: left;
						font-size: 12px;
						font-weight: bold;
					}
					.mc_form_field input,
					.mc_form_field select {
						/* width: 200px; */
					}
					.mc_form_field input {
						margin: 0 0 10px 0;
						background: #f4f4f4;
						color: #444;
						width: 80%;
						font-size: 11px;
						padding: 5px;
					}
					.mc_form_field select {
						padding: 2px;
						height: 2em;
						margin: 0 0 10px 0;
						background: #f4f4f4;
						color: #444;
						width: 60%;
						font-size: 11px;
					}
					.mc_desc {
						font-size: 10px;
						color: #aaa;
						display: block;
					}
				</style>'
				;
		}
	}
	
	// add to admin head
	add_action( 'admin_head', 'MC_styles' );
	
	
	// adds a title to the editor
	function MC_editor_title() {
	
		// get post
		global $post;
		
		// if we're viewing mc_rule post type
		if ( 'mc_rule' == $post->post_type ) {
		
			// our title
			echo '<h3>Enter your CSS styles here.</h3>';
		}
	}
	
	// add to media buttons section as there isn't a suitable place for it otherwise
	add_action( 'media_buttons', 'MC_editor_title' );
	
	
	function MC_options_form( $options, $current_values = array() ) {
	
		if ( !empty( $options ) && is_array( $options ) ) {
		
			$slide_toggle_array = array();
			
			$shortname = 'mc_';
			
			foreach ( $options as $value ) {
			
				$current_value = '';
				
				if ( !empty( $current_values[$value['id']] ) ) {
					$current_value = $current_values[$value['id']];
				}
				
				if ( empty( $current_value ) && !empty( $value['std'] ) ) {
					$current_value = $value['std'];
				}
				
				$current_value = stripslashes( $current_value );
				
				$desc = '';
				if ( !empty( $value['desc'] ) ) {
					$desc = '<div class="' . $shortname . 'desc ' . $value['id'] . '_desc">' . $value['desc'] . '</div>';
				}
				
				$note = '';
				if ( !empty( $value['note'] ) ) {
					$note = '<div class="' . $shortname . 'note ' . $value['id'] . '_note">' . $value['note'] . '</div>';
				}
				
				$class = '';
				if ( !empty( $value['class'] ) ) {
					$class = $value['class'];
				}
				
				$readonly = '';
				if ( $value['readonly'] === true  ) {
					$readonly = ' readonly="readonly" ';
				}
				
				echo '
					<div class="' . $shortname . 'form_field ' . $value['rowclass'] . ' ' . $value['id'] . '_form_field ' . $shortname . 'form_field_' . $value['type'] . '">
						<label for="' . $value['id'] . '">' . $value['name'] . '</label>
						<div class="mc_input_container">
					';
				
				if ($value['type'] == "text") { 
				
					echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" class="' . $class . '" ' . $readonly . '  type="' . $value['type'] . '" value="' . $current_value . '" size="40" />';
				
				} elseif ($value['type'] == "textarea") { 
				
					echo '<textarea name="' . $value['id'] . '" id="' . $value['id'] . '" class="' . $class . '" ' . $readonly . ' cols="50" rows="8"/>' . $current_value . '</textarea>';
				
				} elseif ($value['type'] == "select") { 
				
					echo '<select name="' . $value['id']  . '" id="' . $value['id'] . '" class="' .  $class . '">';
					
						$array_values = array_values( $value['options'] );
						if ( $array_values === $value['options'] ) {
							$indexed_array = true;
						} else {
							$indexed_array = false;
						}
						
						if ( $indexed_array !== false ) { 
							foreach ( $value['options'] as $option ) {
							
								$selected = '';
								if ( $current_value == $option) { 
									$selected =  ' selected="selected"'; 
								}
								echo '<option ' . $selected . '>' . $option . '</option>';
							}
						} else {
						
							foreach ( $value['options'] as $option => $key_value ) {
							
								$selected = '';
								if ( $current_value == $option) { 
									$selected =  ' selected="selected"'; 
								}
								echo '<option ' . $selected . ' value="' . $option . '">' . $key_value . '</option>';
							}
						}
					
					echo '</select>';
				} elseif ( $value['type'] == "heading" ) {
				
					echo '<h3>' . $value['name'] . '</h3>';
				
				} elseif ( $value['type'] == "checkbox" ) {
				
					if( $current_value ){ 
						$checked = "checked=\"checked\""; 
					} else { 
						$checked = ""; 
					}
					
					echo '<input type="checkbox" name="' . $value['id'] . '" id="' . $value['id'] .'" class="' . $class . '" value="true" ' . $checked . ' />';
				}
				
				echo $desc . $note . '</div></div>';
				
				if ( !empty( $value['slidetoggle'] ) ) {
					$slide_toggle_array[] = $value['id'];
				}
			}
			
			if ( !empty( $slide_toggle_array ) ) {
				
				foreach ( $slide_toggle_array as $name ) {
				
					echo '<script type="text/javascript" charset="utf-8">
							jQuery(document).ready(function(){
								jQuery( "#' . $name . '" ).change(function(){
								
									jQuery( ".' . $name . '" ).slideUp(); // wont work on table rows, needs display:block;
									// jQuery( ".' . $name . '" ).hide();
									jQuery( ".' . $name . '." + this.value + "" ).slideToggle();
								});
								
								jQuery(".' . $name . '").hide();
								jQuery("#' . $name . '").change();
							});
						</script>
						';
				}
			}
		}
	}
?>