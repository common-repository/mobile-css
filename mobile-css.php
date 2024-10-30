<?php
/*
Plugin Name: Mobile CSS
Plugin URI: http://playforward.net
Description: A plugin to create special CSS rules for specific mobile instances.
Version: 1.2
Author: Dustin Dempsey
Author URI: http://playforward.net
*/

// include PRO files
if ( file_exists( dirname( __FILE__ ) . '/pro/pro.php' ) ) {
	@include_once( dirname( __FILE__ ) . '/pro/pro.php' );
} else {
	define( "MOBILECSSISPRO", false );
}

// include options
@include_once( dirname( __FILE__ ) . '/mc-includes/mc.options.php' );

// include mobile detect class
if ( !class_exists( 'Mobile_Detect' ) ) {
	@include_once( dirname( __FILE__ ) . '/mc-includes/Mobile_Detect.php' );
}

// include post types
@include_once( dirname( __FILE__ ) . '/mc-includes/mc.post_types.php' );

// include meta boxes
@include_once( dirname( __FILE__ ) . '/mc-includes/mc.meta_box.php' );

// include functions
@include_once( dirname( __FILE__ ) . '/mc-includes/mc.functions.php' );


?>