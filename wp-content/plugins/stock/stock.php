<?php
/**
 * @package Stock
 * @version 1.0
 */
/*
Plugin Name: Stock
Plugin URI: http://medifar.ar/
Description: Conecta y actualiza stock.
Author: Diego Moreno
Version: 1.0
Author URI: http://www.pymesign.com/
*/

// create a scheduled event (if it does not exist already)
function cronstarter_activation() {
	if( !wp_next_scheduled( 'mycronjob' ) ) {  
	   wp_schedule_event( time(), 'hourly', 'mycronjob' );  
	}
}
// and make sure it's called whenever WordPress loads
add_action('wp', 'cronstarter_activation');

// unschedule event upon plugin deactivation
function cronstarter_deactivate() {	
	// find out when the last event was scheduled
	$timestamp = wp_next_scheduled ('mycronjob');
	// unschedule previous event if any
	wp_unschedule_event ($timestamp, 'mycronjob');
} 
register_deactivation_hook (__FILE__, 'cronstarter_deactivate');

// here's the function we'd like to call with our cron job
function my_repeat_function() {

	// do here what needs to be done automatically as per your schedule
	// in this example we're sending an email
	
	// components for our email
	$recepients = 'info@pymesign.com';
	$subject = 'Hello from your Cron Job';
	$message = 'This is a test mail sent by WordPress automatically as per your schedule.';
	
	// let's send it 
	mail($recepients, $subject, $message);
	
	/*echo 'Archivos en directorio: <br />';
	$directorio = "../CARRITO-WEB/INFO-PARA-WEB";
	$archivos = scandir($directorio, 1);
	print_r($archivos);*/

	/*foreach ($archivos as $i => $value) {
		printf('Path: %s', get_home_path() . '<br>');
		printf('ABSPATH: %s', ABSPATH . '<br>');
		$abcfile = ABSPATH . 'CARRITO-WEB/INFO-PARA-WEB/' . $archivos[$i];
		print($abcfile . '<br>');

		$myfile = fopen($abcfile, "r") or die("Unable to open file!");
		
		$contenido = fread($myfile, filesize($abcfile));

		echo 'My file es: ' .$abcfile;

		$contenido = explode("=", $contenido);

		$sku = (int)$contenido[0];
		echo 'Producto sku: ' . $sku . '<br>';
		$stock = (int)$contenido[1];

		$prodid = wc_get_product_id_by_sku($sku);
		echo 'Actualizando ID: ' . $prodid . '<br>';

		global $wpdb;
		$table = 'wp_postmeta';

		$data = array(
			'meta_value' => $stock
		);
		$wherecondition = array(
			'post_id' => $prodid,
			'meta_key' => '_stock'
		);
		$wpdb->update($table, $data, $wherecondition);

		unlink($abcfile);
	}*/
}

// hook that function onto our scheduled event:
add_action ('mycronjob', 'my_repeat_function'); 

function stock_admin_menu()
{
	add_menu_page(
		'Stock', // page title
		'Stock', // menu title
		'manage_options', // capability
		'stock', // menu slug
		'my_repeat_function' // callback function
	);
}
add_action('admin_menu', 'stock_admin_menu');