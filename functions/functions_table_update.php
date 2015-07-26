<?php
/**
*** Add data to data table
**/

function inmab_load_update( $formid, $ab_id = '', $formsubmit = 0, $formclick = 0 ) {
	global $wpdb;
	//global $table_name;
	$table_name = $wpdb->prefix . TABLE_NAME;
	
	// set update values
	if( ( !$formid ) || ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'] ) ) ) {
		die;
	};
		
	if( !$ab_id ) {	
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ),
				'form_id' => $formid
			) 
		);
		
		$ab_id = $wpdb->insert_id;
		
	} else {
		$wpdb->update( 
			$table_name, 
			array(  
				'form_click' => $formclick,  
				'form_submit' => $formsubmit
			), 
			array( 'entry_id' => $ab_id )
		);
	}
		
	return $ab_id;
	
}

/**
*** Add submit to data table
**/

function inmab_submit_update( $ab_id = '', $formsubmit ) {
	global $wpdb;
	//global $table_name;
	$table_name = $wpdb->prefix . TABLE_NAME;
	
	// set update values
	if( !$formsubmit ) {
		die;
	}
	
	$wpdb->update( 
		$table_name, 
		array(   
			'form_submit' => $formsubmit
		), 
		array( 'entry_id' => $ab_id )
	);
		
	return $ab_id;
	
}

/**
*** Add form click to data table
*

function inmab_click_update( $ab_id = '', $formclick ) {
	global $wpdb;
	global $table_name;
	
	// set update values
	if( !$formclick ) {
		die;
	}
	
	$wpdb->update( 
		$table_name, 
		array(  
			'form_click' => $formclick
		), 
		array( 'entry_id' => $ab_id )
	);
		
	return $ab_id;
	
}
*/

/**
*** Reset all test data from data table
**/

function inmab_reset_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . TABLE_NAME;
	
	$delete = $wpdb->query("TRUNCATE TABLE $table_name");
	
}
?>