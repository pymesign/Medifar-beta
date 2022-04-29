<?php

include_once dirname(__FILE__) . "/vendor/autoload.php";
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

/**
 * Plugin Name: WooCommerce Decidir Gateway
 * Plugin URI: https://www.skyverge.com/?p=3343
 * Description: Clones the "Cheque" gateway to create another manual / offline payment method; can be used for testing as well.
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com/
 * Version: 1.0.2
 * Text Domain: wc-gateway-decidir
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2015-2016 SkyVerge, Inc. (info@skyverge.com) and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Gateway-Decidir
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2015-2016, SkyVerge, Inc. and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * This offline gateway forks the WooCommerce core "Cheque" payment gateway to create another offline payment method.
 */

defined('ABSPATH') or exit;

// Make sure WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	return;
}


/**
 * Add the gateway to WC Available Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + offline gateway
 */
function wc_decidir_add_to_gateways($gateways)
{
	$gateways[] = 'WC_Gateway_Decidir';
	return $gateways;
}
add_filter('woocommerce_payment_gateways', 'wc_decidir_add_to_gateways');


/**
 * Adds plugin page links
 * 
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */
function wc_decidir_gateway_plugin_links($links)
{

	$plugin_links = array(
		'<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=decidir_gateway') . '">' . __('Configure', 'wc-gateway-decidir') . '</a>'
	);

	return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wc_decidir_gateway_plugin_links');


/**
 * Offline Payment Gateway
 *
 * Provides an Offline Payment Gateway; mainly for testing purposes.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class 		WC_Gateway_Decidir
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author 		SkyVerge
 */
add_action('plugins_loaded', 'wc_decidir_gateway_init', 11);

function wc_decidir_gateway_init()
{

	class WC_Gateway_Decidir extends WC_Payment_Gateway
	{

		/**
		 * Constructor for the gateway.
		 */
		public function __construct()
		{

			$this->id                 = 'decidir_gateway';
			$this->icon               = apply_filters('woocommerce_offline_icon', '');
			$this->has_fields         = true;
			$this->method_title       = __('Decidir', 'wc-gateway-decidir');
			$this->method_description = __('Allows offline payments. Very handy if you use your cheque gateway for another payment method, and can help with testing. Orders are marked as "on-hold" when received.', 'wc-gateway-decidir');

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables
			$this->title        = $this->get_option('title');
			$this->description  = $this->get_option('description');
			$this->instructions = $this->get_option('instructions', $this->description);

			// Actions
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
			//add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
			add_action('woocommerce_decidir_gateway_return_url', [$this, 'decidir_return_url']);

			// Customer Emails
			add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);
		}

		/**
		 * Builds our payment fields area - including tokenization fields for logged
		 * in users, and the actual payment fields.
		 *
		 * @since 2.6.0
		 */
		public function payment_fields()
		{
			if ($this->supports('tokenization') && is_checkout()) {
				$this->tokenization_script();
				$this->saved_payment_methods();
				$this->form();
				$this->save_payment_method_checkbox();
			} else {
				$this->form();
			}
		}

		/**
		 * Output field name HTML
		 *
		 * Gateways which support tokenization do not require names - we don't want the data to post to the server.
		 *
		 * @since  2.6.0
		 * @param  string $name Field name.
		 * @return string
		 */
		public function field_name($name)
		{
			return $this->supports('tokenization') ? '' : ' name="' . esc_attr($this->id . '-' . $name) . '" ';
		}

		/**
		 * Outputs fields for entering credit card information.
		 *
		 * @since 2.6.0
		 */
		public function form()
		{
			wp_enqueue_script('wc-credit-card-form');

			$fields = array();

			$cvc_field = '<p class="form-row form-row-first">
			<label for="' . esc_attr($this->id) . '-card-cvc">' . esc_html__('Card code', 'woocommerce') . '&nbsp;<span class="required">*</span></label>
			<input id="' . esc_attr($this->id) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc" inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="4" placeholder="' . esc_attr__('CVC', 'woocommerce') . '" ' . $this->field_name('card-cvc') . ' style="width:100px" />
		</p>';

			$default_fields = array(
				'card-number-field' => '<p class="form-row form-row-wide">
				<label for="' . esc_attr($this->id) . '-card-number">' . esc_html__('Card number', 'woocommerce') . '&nbsp;<span class="required">*</span></label>
				<input id="' . esc_attr($this->id) . '-card-number" class="input-text wc-credit-card-form-card-number" inputmode="numeric" autocomplete="cc-number" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;" ' . $this->field_name('card-number') . ' />
			</p>',
				'card-expiry-field' => '<p class="form-row form-row-first">
				<label for="' . esc_attr($this->id) . '-card-expiry">' . esc_html__('Expiry (MM/YY)', 'woocommerce') . '&nbsp;<span class="required">*</span></label>
				<input id="' . esc_attr($this->id) . '-card-expiry" class="input-text wc-credit-card-form-card-expiry" inputmode="numeric" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . esc_attr__('MM / YY', 'woocommerce') . '" ' . $this->field_name('card-expiry') . ' />
			</p>',
			'card-holder-name-field' => '<p class="form-row form-row-name">
				<label for="' . esc_attr($this->id) . '-card-holder-name">' . esc_html__('Nombre', 'woocommerce') . '&nbsp;<span class="required">*</span></label>
				<input id="' . esc_attr($this->id) . '-card-holder-name" class="input-text wc-credit-card-form-card-holder-name" inputmode="text" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . esc_attr__('Ingrese el nombre como figura en la tarjeta', 'woocommerce') . '" ' . $this->field_name('card-holder-name') . ' />
			</p>',
			);

			if (!$this->supports('credit_card_form_cvc_on_saved_method')) {
				$default_fields['card-cvc-field'] = $cvc_field;
			}

			$fields = wp_parse_args($fields, apply_filters('woocommerce_credit_card_form_fields', $default_fields, $this->id));
?>

			<fieldset id="wc-<?php echo esc_attr($this->id); ?>-cc-form" class='wc-credit-card-form wc-payment-form'>
				<?php do_action('woocommerce_credit_card_form_start', $this->id); ?>
				<?php
				foreach ($fields as $field) {
					echo $field; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
				}
				?>
				<?php do_action('woocommerce_credit_card_form_end', $this->id); ?>
				<div class="clear"></div>
			</fieldset>
<?php

			if ($this->supports('credit_card_form_cvc_on_saved_method')) {
				echo '<fieldset>' . $cvc_field . '</fieldset>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Validate frontend fields.
		 *
		 * Validate payment fields on the frontend.
		 *
		 * @return bool
		 */
		public function validate_fields()
		{
			        
			$card_number = str_replace( array(' ', '-' ), '', $_POST['decidir_gateway-card-number'] ); 
			$card_cvv=(isset($_POST['decidir_gateway-card-cvc'])) ? $_POST ['decidir_gateway-card-cvc'] : ''; 
			$x_exp_date_aux=isset($_POST['decidir_gateway-card-cvc']) ? explode ("/", $_POST['decidir_gateway-card-expiry']) :  array('', ''); 
			$card_exp_month =  str_replace( array(' ', '-' ), '', $x_exp_date_aux[0]); 
			$card_exp_year =  str_replace( array(' ', '-' ), '', $x_exp_date_aux[1]);
			$card_name = $_POST['decidir_gateway-card-holder-name'];
		 
			//Check name
			if(empty($card_name)) { 
				wc_add_notice('Card holder name is required', 'error'); 
				return false; 
			}
			
			
			// Check card number 
			if(empty($card_number) || !ctype_digit($card_number)) { 
				wc_add_notice('Card number is required', 'error'); 
				return false; 
			} 
			 
			// Check card security code 
			 
			if(!ctype_digit($card_cvv)) { 
				wc_add_notice('Card security code is invalid (only digits are allowed)', 'error'); 
				return false; 
			} 
			if(strlen($card_cvv) <3) { 
				wc_add_notice('Card security code, invalid length', 'error'); 
				return false; 
			} 
		 
			if(empty($card_exp_year)) { 
				wc_add_notice('Card expiration year is required', 'error'); 
				return false; 
			}else{ 
				if(strlen($card_exp_year)==1 ||strlen($card_exp_year)==3||strlen($card_exp_year)>4) { 
					wc_add_notice('Card expiration year is invalid', 'error'); 
					return false; 
				} 
		 
				if(strlen($card_exp_year)==2) { 
					if((int)$card_exp_year < (int)substr(date('Y'), -2)) { 
						wc_add_notice('Card expiration year is invalid 1', 'error'); 
						return false; 
					} 
				} 
		 
				if(strlen($card_exp_year)==4) { 
					if((int)$card_exp_year < (int)date('Y')) { 
						wc_add_notice('Card expiration year is invalid', 'error'); 
						return false; 
					} 
				} 
			} 
			if(empty($card_exp_month)) { 
				wc_add_notice('Card expiration mont is required', 'error'); 
				return false; 
			}else{ 
				if((int)$card_exp_month>12 || (int)$card_exp_month<1) { 
					wc_add_notice('Card expiration month is invalid', 'error'); 
					return false; 
				} 
			} 

			return true;
		}


		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields()
		{

			$this->form_fields = apply_filters('wc_decidir_form_fields', array(

				'enabled' => array(
					'title'   => __('Enable/Disable', 'wc-gateway-decidir'),
					'type'    => 'checkbox',
					'label'   => __('Enable Decidir Payment', 'wc-gateway-decidir'),
					'default' => 'yes'
				),

				'title' => array(
					'title'       => __('Title', 'wc-gateway-decidir'),
					'type'        => 'text',
					'description' => __('This controls the title for the payment method the customer sees during checkout.', 'wc-gateway-decidir'),
					'default'     => __('Decidir Payment', 'wc-gateway-decidir'),
					'desc_tip'    => true,
				),

				'description' => array(
					'title'       => __('Description', 'wc-gateway-decidir'),
					'type'        => 'textarea',
					'description' => __('Payment method description that the customer will see on your checkout.', 'wc-gateway-decidir'),
					'default'     => __('Please remit payment to Store Name upon pickup or delivery.', 'wc-gateway-decidir'),
					'desc_tip'    => true,
				),

				'instructions' => array(
					'title'       => __('Instructions', 'wc-gateway-decidir'),
					'type'        => 'textarea',
					'description' => __('Instructions that will be added to the thank you page and emails.', 'wc-gateway-decidir'),
					'default'     => '',
					'desc_tip'    => true,
				),
			));
		}


		/**
		 * Output for the order received page.
		 */
		public function thankyou_page()
		{
			if ($this->instructions) {
				echo wpautop(wptexturize($this->instructions));
			}
		}


		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool $sent_to_admin
		 * @param bool $plain_text
		 */
		public function email_instructions($order, $sent_to_admin, $plain_text = false)
		{

			if ($this->instructions && !$sent_to_admin && $this->id === $order->payment_method && $order->has_status('on-hold')) {
				echo wpautop(wptexturize($this->instructions)) . PHP_EOL;
			}
		}


		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment($order_id)
		{

			$order = wc_get_order($order_id);

			$tarjeta_numero = $_POST['decidir_gateway-card-number'];
			$tarjeta_cvc = $_POST['decidir_gateway-card-cvc'];
			$tarjeta_expiry = $_POST['decidir_gateway-card-expiry'];
			$tarjeta_nombre = $_POST['decidir_gateway-card-holder-name'];
			$new_str = str_replace(' ', '', $tarjeta_expiry);
			$explode_new_str = explode('/', $new_str);
			$mes = $explode_new_str[0];
			$anio = $explode_new_str[1];

			$total = WC()->cart->total * 100;

			/*decidir ejemplo*/

			/* solicitud del token */

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://developers.decidir.com/api/v2/tokens",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "{\"card_number\":\"$tarjeta_numero\",\"card_expiration_month\":\"$mes\",\"card_expiration_year\":\"$anio\",\"security_code\":\"$tarjeta_cvc\",\"card_holder_name\":\"$tarjeta_nombre\",\"card_holder_identification\":{\"type\":\"dni\",\"number\":\"\"}}",
				CURLOPT_HTTPHEADER => array(
					"apikey: 4ae76f00234843d1af5994ed4674fd76",
					"cache-control: no-cache",
					"content-type: application/json"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				echo 'response token: ' . $response;
			}
			
			//serializamos los datos
			$obj = json_decode($response);
			$token = $obj->{'id'};
			$bin = $obj->{'bin'};

			/* solicitud del pago */

			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://developers.decidir.com/api/v2/payments",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"site_transaction_id\":\"$order_id\",\"token\":\"$token\",\"payment_method_id\":1,\"bin\":\"$bin\",\"amount\":$total,\"currency\":\"ARS\",\"installments\":1,\"description\":\"\",\"payment_type\":\"single\",\"sub_payments\":[]}",
			CURLOPT_HTTPHEADER => array(
				"apikey: 3891f691dc4f40b6941a25a68d17c7f4",
				"cache-control: no-cache",
				"content-type: application/json"
			),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			echo "cURL Error #:" . $err;
			} else {
			echo 'response pago: '.$response;
			}

			/* fin decidir ejemplo */

			
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status('on-hold', __('Awaiting offline payment', 'wc-gateway-decidir'));

			// Reduce stock levels
			$order->reduce_order_stock();

			// Remove cart
			WC()->cart->empty_cart();

			// Return thankyou redirect
			return array(
				'result' 	=> 'success',
				'redirect'	=> $this->get_return_url($order)
			);
			
		}
	} // end \WC_Gateway_Decidir class
}
