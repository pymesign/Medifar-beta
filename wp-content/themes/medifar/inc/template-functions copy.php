<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package medifar
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function medifar_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (!is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'medifar_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function medifar_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'medifar_pingback_header');

function display_hello_world_page()
{
	//echo 'Archivos en directorio: <br />';
	$directorio = ABSPATH . "CARRITO-WEB/INFO-PARA-WEB";
	$archivos = scandir($directorio, 1);
	//print_r($archivos);

	$count = count($archivos);

	//echo 'tamaño de archivos: ' . $count;

	if ($count > 2) {

		//echo 'Sincronizando';

		foreach ($archivos as $i => $value) {
			//printf('Path: %s', get_home_path() . '<br>');
			//printf('ABSPATH: %s', ABSPATH . '<br>');
			$abcfile = ABSPATH . 'CARRITO-WEB/INFO-PARA-WEB/' . $archivos[$i];
			//print($abcfile . '<br>');

			$myfile = fopen($abcfile, "r") or die("Unable to open file!");

			$contenido = fread($myfile, filesize($abcfile));

			//echo 'My file es: ' .$abcfile;

			$contenido = explode("=", $contenido);

			$sku = $contenido[0];
			//echo 'Producto sku: ' . $sku . '<br>';			

			$stock = (int)$contenido[1];

			//debugeamos por mail que nro de sku vamos a actualizar	
			/*$to = 'info@pymesign.com';
			$subject = 'Actualizando sku';
			$body = $sku . " = " . $stock;
			$headers = array('Content-Type: text/html; charset=UTF-8');
				
			wp_mail( $to, $subject, $body, $headers );*/

			$prodid = wc_get_product_id_by_sku($sku);
			//echo 'Actualizando ID: ' . $prodid . '<br>';

			/*global $wpdb;
			$table = 'wp_postmeta';

			$data = array(
				'meta_value' => $stock
			);
			$wherecondition = array(
				'post_id' => $prodid,
				'meta_key' => '_stock'
			);
			$wpdb->update($table, $data, $wherecondition);*/
			wc_update_product_stock( $prodid, $stock, 'set');				

			unlink($abcfile);
		}
	} else {

		//echo 'No se ha sincronizado nada';
	}
}

add_action('wp_loaded', 'display_hello_world_page');

// Hook in
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields($fields)
{
	$fields['billing']['matricula_profesional'] = array(
		'label'     => __('Nro. matrícula profesional (venta exclusiva a profesionales)', 'woocommerce'),
		'placeholder'   => _x('Informe el número de matrícula profesional para realizar su compra', 'placeholder', 'woocommerce'),
		'required'  => true,
		'class'     => array('form-row-wide'),
		'clear'     => true
	);

	return $fields;
}

//Save Field in the DB as Order Meta Data:
add_action('woocommerce_checkout_update_order_meta', 'matricula_update_order_meta');

function matricula_update_order_meta($order_id)
{

	if (!empty($_POST['matricula_profesional'])) {
		update_post_meta($order_id, 'matricula_profesional', esc_attr($_POST['matricula_profesional']));
	}
}

/**
 * Display field value on the order edit page
 */

add_action('woocommerce_admin_order_data_after_shipping_address', 'matricula_display_admin_order_meta', 10, 1);

function matricula_display_admin_order_meta($order)
{
	echo '<p><strong>' . __('Matrícula profesional') . ':</strong> ' . get_post_meta($order->get_id(), 'matricula_profesional', true) . '</p>';
}


//Save Field Cuotas in the DB as Order Meta Data:
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

function my_custom_checkout_field_update_order_meta($order_id)
{

	if (!empty($_POST['decidir_gateway-installments-name'])) {
		update_post_meta($order_id, 'cuotas', esc_attr($_POST['decidir_gateway-installments-name']));
	}
}


//display Cuotas in the Order details screen:
add_action('woocommerce_admin_order_data_after_billing_address', 'my_custom_billing_fields_display_admin_order_meta', 10, 1);

function my_custom_billing_fields_display_admin_order_meta($order)
{
	echo '<p><strong>' . __('Cuotas') . ':</strong><br> ' . get_post_meta($order->get_id(), 'cuotas', true) . '</p>';
}


add_action( 'woocommerce_order_details_after_order_table',
            'cuotas_display_cust_order_meta', 10, 1 );

function cuotas_display_cust_order_meta( $order ) {
  echo '<strong>Cuotas:</strong>';
  /* translators: whatever */
  echo '<p>' . get_post_meta($order->get_id(), 'cuotas', true) . '</p>';
  
}


//display Total financiado in the Order details screen:
add_action('woocommerce_admin_order_data_after_billing_address', 'total_financiado_fields_display_admin_order_meta', 10, 1);

function total_financiado_fields_display_admin_order_meta($order)
{
	echo '<p><strong>' . __('Total financiado') . ':</strong><br>$ ' . get_post_meta($order->get_id(), 'total_financiado', true) . '</p>';
}


add_action( 'woocommerce_order_details_after_order_table',
            'total_financiado_display_cust_order_meta', 10, 1 );

function total_financiado_display_cust_order_meta( $order ) {
  echo '<strong>Total financiado:</strong>';
  /* translators: whatever */
  echo '<p>$ ' . get_post_meta($order->get_id(), 'total_financiado', true) . '</p>';
  
  echo '<p><strong>' . __('Matrícula profesional') . ':</strong> ' . get_post_meta($order->get_id(), 'matricula_profesional', true) . '</p>';
  
}

//agregamos los campos cuotas y total financiado al email que recibe el cliente tras el pedido
add_action( 'woocommerce_email_order_meta', 'misha_add_email_order_meta', 10, 3 );
/*
 * @param $order_obj Order Object
 * @param $sent_to_admin If this email is for administrator or for a customer
 * @param $plain_text HTML or Plain text (can be configured in WooCommerce > Settings > Emails)
 */
function misha_add_email_order_meta( $order_obj, $sent_to_admin, $plain_text ){
	 
	// get all the  fields
	$email_cuotas = get_post_meta( $order_obj->get_order_number(), 'cuotas', true );
	$email_totalf = get_post_meta( $order_obj->get_order_number(), 'total_financiado', true );
 
 
	// ok, we will add the separate version for plaintext emails
	if ( $plain_text === false ) {
 
		// you shouldn't have to worry about inline styles, WooCommerce adds them itself depending on the theme you use
		echo '<h2>Detalles del pago</h2>
		<ul>		
		<li><strong>Cuotas:</strong> ' . $email_cuotas . '</li>
		<li><strong>Total financiado:</strong> $ ' . $email_totalf . '</li>		
		</ul>';

		echo '<p><strong>' . __('Matrícula profesional') . ':</strong> ' . get_post_meta($order_obj->get_id(), 'matricula_profesional', true) . '</p>';
 
	} else {
 
		echo "DETALLES DEL PAGO\n		
		Cuotas: $email_cuotas
		Total financiado: $ $email_totalf";	
 
	}
 
}