<?php
/**
 * Plugin Name: Formidable A/B Tests
 * Plugin URI: http://imnotmarvin.com/formidable-ab-tests/
 * Description: Easily A/B test Formidable Pro created forms. 
 * Version: 0.04
 * Author: Michael Davis
 * Author URI: http://imnotmarvin.com/
 * License: GPL2
 * Copyright 2015  I'm Not Marvin, LLC  (email : me@imnotmarvin.com) 
**/


/**
 * Required files
**/

require_once( plugin_dir_path( __FILE__ ) . 'functions/functions_table_update.php' );
require_once( plugin_dir_path( __FILE__ ) . 'functions/functions_admin_display.php' );


/**
 * Define the data table
**/

define('TABLE_NAME', 'inmab_data');


/**
 * Setup or update the data table
**/

global $inmab_db_version;
//global $table_name;
//$table_name = TABLE_NAME;
$eid = '';


function inmab_install() {
	global $wpdb;
	global $inmab_db_version;
	$inmab_db_version = '1.1';
	$table_name = $wpdb->prefix . TABLE_NAME;
	
	$installed_ver = get_option( "inmab_db_version" );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		entry_id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		form_id mediumint(9) NOT NULL,
		form_click boolean not null default 0,
		form_submit boolean not null default 0,
		UNIQUE KEY id (entry_id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'inmab_db_version', $inmab_db_version );
	
	global $wpdb;
	$installed_ver = get_option( "inmab_db_version" );

	if ( $installed_ver != $inmab_db_version ) {

		$sql = "CREATE TABLE $table_name (
			entry_id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			form_id mediumint(9) NOT NULL,
			form_click boolean not null default 0,
			form_submit boolean not null default 0,
			UNIQUE KEY id (entry_id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "inmab_db_version", $inmab_db_version );
	}
}
register_activation_hook( __FILE__, 'inmab_install' );


/**
 * Check if we need to update data table
**/

function inmab_update_db_check() {
    global $inmab_db_version;
    if ( get_site_option( 'inmab_db_version' ) != $inmab_db_version ) {
        inmab_install();
    }
}
add_action( 'plugins_loaded', 'inmab_update_db_check' );


/**
 * Add the shortcode
**/

function inmab_shortcode_func( $atts ) {
    $a = shortcode_atts( array(
        'forms' => '',
		'title' => false,
		'description' => false,
		'minimize' => ''
		), $atts );

	// make sure we have values
	if( !$a['forms'] ) {
		die;
	}
	
	$forms = str_replace(' ', '', $a['forms']);
	
	$form = explode( ',', $forms );
	$tcount = count( $form );
	$getitem = rand( 0, $tcount - 1 );
	
	global $formid;
	
	
	$formid = $form[$getitem];
	
	
	$ab_entry_id = inmab_load_update( $formid );
	
	global $eid;
	$eid = strval( $ab_entry_id );

	$show_form = FrmFormsController::get_form_shortcode( array( 
		'id' => $formid,
		'title' => $a['title'],
		'description' => $a['description'],
		'minimize' => $a['minimize'],
		'abentry' => $ab_entry_id ) );
		return $show_form;
	}
add_shortcode( 'inm_frm_ab_test', 'inmab_shortcode_func' );



/**
 * Write ab_id value to hidden field for later use
**/

add_action('frm_entry_form', 'add_hidden_field'); 
function add_hidden_field( $form ){
	global $eid, $formid;
	echo '<input type="hidden" name="inmab_update_id" value="' . $eid . '">';
}


/**
 * Get hidden field values
**/

add_filter('frm_pre_create_entry', 'inmab_get_hidden_values');
function inmab_get_hidden_values( $values ){

	if( $values['inmab_update_id'] != '' ){
	
		inmab_submit_update( $values['inmab_update_id'], 1 );
	
	}
  
	return $values;
}


/**
 * Check if we resetting the test data
**/

if( isset( $_POST['delete_all'] ) ) {
	inmab_reset_table();
}

?>