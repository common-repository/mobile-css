<?php

	function MC_device_type() {
	
		$devices = array(
			'All Mobile Devices'	=> 'all',
			'Phones Only'			=> 'phone',
			'Tablets Only'			=> 'tablet'
		);
		
		$devices = array_flip( $devices );
		
		$devices = apply_filters( 'MC_device_type', $devices );
		
		return $devices;
	}

	function MC_phone_devices() {
	
		$devices = array(
			'All'				=> 'all',
			'iPhone/iPod Touch'	=> 'iphone',
			'HTC'				=> 'htc',
			'Motorola'			=> 'motorola',
			'BlackBerry'		=> 'blackberry',
			'Nexus'				=> 'nexus',
			'Dell'				=> 'dell',
			'Samsung'			=> 'samsung',
			'Sony'				=> 'sony',
			'Asus'				=> 'asus',
			'Palm'				=> 'palm',
			'Vertu'				=> 'vertu',
			'Generic Phone'		=> 'genericphone'
		);
		
		$devices = array_flip( $devices );
		
		$devices = apply_filters( 'MC_phone_devices', $devices );
		
		return $devices;
	}

	function MC_tablet_devices() {
	
		$devices = array(
			'All'				=> 'all',
			'iPad'				=> 'ipad',
			'Nexus Tablet'		=> 'nexustablet',
			'Kindle'			=> 'kindle',
			'Samsung Tablet'	=> 'samsungtablet',
			'HTC tablet'		=> 'htctablet',
			'Asus Tablet'		=> 'asustablet',
			'Acer Tablet'		=> 'acertablet',
			'Motorola Tablet'	=> 'motorolatablet',
			'BlackBerry Tablet'	=> 'blackberrytablet',
			'Nook Tablet'		=> 'nooktablet',
			'Yarvik Tablet'		=> 'yarviktablet',
			'Medion Tablet'		=> 'mediontablet',
			'Arnova Tablet'		=> 'arnovatablet',
			'Archos Tablet'		=> 'archostablet',
			'Ainol Tablet'		=> 'ainoltablet',
			'Sony Tablet'		=> 'sonytablet',
			'Generic Tablet'	=> 'generictablet'
		);
		
		$devices = array_flip( $devices );
		
		$devices = apply_filters( 'MC_tablet_devices', $devices );
		
		return $devices;
	}
	
	
	function MC_rule_options() {
	

		/// General Settings
		
		$options = array();
		
		$options[] = array(	
			"name" 			=> "What device type?",
			"desc" 			=> "The device type do you want to apply this rule to.",
			"id" 			=> "mc_device_type",
			"std" 			=> "all",
			"type" 			=> "select",
			"options" 		=> MC_device_type(),
			"slidetoggle"	=> "mc_device_type" // name our options group
			);	
		
		$options[] = array(	
			"name" 			=> "What phone type?",
			"desc" 			=> "The type of phone to apply this rule to.",
			"id" 			=> "mc_phone_type",
			"std" 			=> "all",
			"type" 			=> "select",
			"options" 		=> MC_phone_devices(),
			"rowclass"		=> "mc_device_type phone"
			);	
			
		
		$options[] = array(	
			"name" 			=> "What tablet type?",
			"desc" 			=> "The type of tablet to apply this rule to.",
			"id" 			=> "mc_tablet_type",
			"std" 			=> "all",
			"type" 			=> "select",
			"options" 		=> MC_tablet_devices(),
			"rowclass"		=> "mc_device_type tablet"
			);
		
		$options = apply_filters( 'MC_rule_options', $options );
		
		return $options;
	}


?>