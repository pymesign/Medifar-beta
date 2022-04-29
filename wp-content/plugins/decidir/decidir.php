<?php

include_once dirname(__FILE__) . "/vendor/autoload.php";
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

/**
 * Plugin Name: WooCommerce Decidir Gateway
 * Plugin URI: https://www.pymesign.com/?p=3343
 * Description: Clones the "Cheque" gateway to create another manual / offline payment method; can be used for testing as well.
 * Author: Diego Moreno
 * Author URI: https://www.pymesign.com/
 * Version: 1.0.2
 * Text Domain: wc-gateway-decidir
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2015-2016 Pymesign.com (info@pymesign.com) and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Gateway-Decidir
 * @author    Pymesign
 * @category  Admin
 * @copyright Copyright (c) 2021, Pymesign.com and WooCommerce
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
			add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));

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

			$subtotal = WC()->cart->total;

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
				'type-name-field' => '<p class="form-row form-row-name">
				<label for="' . esc_attr($this->id) . 'card-type-name">' . esc_html__('Seleccione tarjeta', 'woocommerce') . '&nbsp;<span class="required">*</span></label>
				<select id="' . esc_attr($this->id) . 'card-type-name" class="input-text wc-credit-card-form-type-name" inputmode="select" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . esc_attr__('Selecciona el medio de pago', 'woocommerce') . '" ' . $this->field_name('card-type-name') . ' />
					<option value="1">Visa</option>
					<option value="31">Visa Débito</option>
					<option value="104">Mastercard</option>
					<option value="105">Mastercard Debit Prisma</option>					
					<option value="63">Cabal Prisma</option>					
					<option value="108">Cabal Débito Prisma</option>					
				</select>
			</p>',
				'installments-name-field' => '<p class="form-row form-row-name">
				<label for="' . esc_attr($this->id) . 'installments-name">' . esc_html__('Seleccione cuotas', 'woocommerce') . '&nbsp;<span class="required">*</span></label>
				<select id="' . esc_attr($this->id) . 'installments-name" class="input-text wc-credit-installments-name" inputmode="select" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . esc_attr__('Selecciona cantidad de cuotas', 'woocommerce') . '" ' . $this->field_name('installments-name') . ' />
					<option value="1">1 pago de ' . $subtotal . '</option>
					<option value="3">3 pagos de ' . $this->calculo_cuota($subtotal, 3) . '</option>
					<option value="6">6 pagos de ' . $this->calculo_cuota($subtotal, 6) . '</option>
					<option value="9">9 pagos de ' . $this->calculo_cuota($subtotal, 9) . '</option>
					<option value="12">12 pagos de ' . $this->calculo_cuota($subtotal, 12) . '</option>
					<option value="18">18 pagos de ' . $this->calculo_cuota($subtotal, 18) . '</option>
					<option value="24">24 pagos de ' . $this->calculo_cuota($subtotal, 24) . '</option>
				</select>
			</p>'
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

			$card_number = str_replace(array(' ', '-'), '', $_POST['decidir_gateway-card-number']);
			$card_cvv = (isset($_POST['decidir_gateway-card-cvc'])) ? $_POST['decidir_gateway-card-cvc'] : '';
			$x_exp_date_aux = isset($_POST['decidir_gateway-card-cvc']) ? explode("/", $_POST['decidir_gateway-card-expiry']) :  array('', '');
			$card_exp_month =  str_replace(array(' ', '-'), '', $x_exp_date_aux[0]);
			$card_exp_year =  str_replace(array(' ', '-'), '', $x_exp_date_aux[1]);
			$card_name = $_POST['decidir_gateway-card-holder-name'];
			$card_type = $_POST['decidir_gateway-card-type-name'];
			$installments = $_POST['decidir_gateway-installments-name'];

			//Check name
			if (empty($card_name)) {
				wc_add_notice('Ingrese el nombre del titular como figura en la tarjeta', 'error');
				return false;
			}


			// Check card number 
			if (empty($card_number) || !ctype_digit($card_number)) {
				wc_add_notice('Ingrese el número de la tarjeta', 'error');
				return false;
			}

			// Check card security code 

			if (!ctype_digit($card_cvv)) {
				wc_add_notice('El código de seguridad es inválido (ingrese dígitos solamente)', 'error');
				return false;
			}
			if (strlen($card_cvv) < 3) {
				wc_add_notice('El código de seguridad es inválido, revise dígitos', 'error');
				return false;
			}

			if ($installments > 1 && ($card_type == '31' || $card_type == '105' || $card_type == '108')) {
				wc_add_notice('Para el tipo de tarjeta seleccionada sólo está disponible el pago en una cuota', 'error');
				return false;
			}

			if (empty($card_exp_year)) {
				wc_add_notice('El año de expiración es requerido', 'error');
				return false;
			} else {
				if (strlen($card_exp_year) == 1 || strlen($card_exp_year) == 3 || strlen($card_exp_year) > 4) {
					wc_add_notice('El año de expiración es inválido', 'error');
					return false;
				}

				if (strlen($card_exp_year) == 2) {
					if ((int)$card_exp_year < (int)substr(date('Y'), -2)) {
						wc_add_notice('El año de expiración es inválido', 'error');
						return false;
					}
				}

				if (strlen($card_exp_year) == 4) {
					if ((int)$card_exp_year < (int)date('Y')) {
						wc_add_notice('El año de expiración es inválido', 'error');
						return false;
					}
				}
			}
			if (empty($card_exp_month)) {
				wc_add_notice('El mes de expiración es inválido', 'error');
				return false;
			} else {
				if ((int)$card_exp_month > 12 || (int)$card_exp_month < 1) {
					wc_add_notice('El mes de expiración es inválido', 'error');
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
					'default'     => __('Tarjeta de Crédito/Débito', 'wc-gateway-decidir'),
					'desc_tip'    => true,
				),

				'description' => array(
					'title'       => __('Description', 'wc-gateway-decidir'),
					'type'        => 'textarea',
					'description' => __('Payment method description that the customer will see on your checkout.', 'wc-gateway-decidir'),
					'default'     => __('Los siguientes items han sido cargados a su tarjeta:', 'wc-gateway-decidir'),
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
		 * Output for the order received page.
		 */
		public function calculo_cuota($subtotal, $cuotas)
		{
			switch ($cuotas) {
				case '3':
					$subtotal = round(($subtotal * 1.1064), 2);
					$valor = $subtotal / 3;
					break;
				case '6':
					$subtotal = round(($subtotal * 1.1938), 2);
					$valor = $subtotal / 6;
					break;
				case '9':
					$subtotal = round(($subtotal * 1.3041), 2);
					$valor = $subtotal / 9;
					break;
				case '12':
					$subtotal = round(($subtotal * 1.4067), 2);
					$valor = $subtotal / 12;
					break;
				case '18':
					$subtotal = round(($subtotal * 1.6254), 2);
					$valor = $subtotal / 18;
					break;
				case '24':
					$subtotal = round(($subtotal * 1.8616), 2);
					$valor = $subtotal / 24;
			}

			$valor = round($valor, 2);

			return $valor;
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
			$tarjeta_tipo = $_POST['decidir_gateway-card-type-name'];
			$cuotas = $_POST['decidir_gateway-installments-name'];
			$new_str = str_replace(' ', '', $tarjeta_expiry);
			$explode_new_str = explode('/', $new_str);
			$mes = $explode_new_str[0];
			$anio = $explode_new_str[1];

			$total = WC()->cart->total;

			if ($cuotas > 1) {
				switch ($cuotas) {
					case '3':
						$total = round(($total * 1.1064), 2);
						break;
					case '6':
						$total = round(($total * 1.1938), 2);
						break;
					case '9':
						$total = round(($total * 1.3041), 2);
						break;
					case '12':
						$total = round(($total * 1.4067), 2);
						break;
					case '18':
						$total = round(($total * 1.6254), 2);
						break;
					case '24':
						$total = round(($total * 1.8616), 2);
				}
			}

			$total = $total * 100;

			/*decidir ejemplo*/

			/* solicitud del token */

			$curl = curl_init();

			curl_setopt_array($curl, array(
				//CURLOPT_URL => "https://developers.decidir.com/api/v2/tokens", // ambiente test
				CURLOPT_URL => "https://live.decidir.com/api/v2/tokens", // ambiente producción
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "{\"card_number\":\"$tarjeta_numero\",\"card_expiration_month\":\"$mes\",\"card_expiration_year\":\"$anio\",\"security_code\":\"$tarjeta_cvc\",\"card_holder_name\":\"$tarjeta_nombre\",\"card_holder_identification\":{\"type\":\"dni\",\"number\":\"\"}}",
				CURLOPT_HTTPHEADER => array(
					//"apikey: 96e7f0d36a0648fb9a8dcb50ac06d260", //public key test
					"apikey: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", //public key producción
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
				//echo 'response token: ' . $response;
				$to = 'info@pymesign.com';
				$subject = 'Response token';
				$body = $response;
				$headers = array('Content-Type: text/html; charset=UTF-8');

				wp_mail($to, $subject, $body, $headers);
			}

			//serializamos los datos
			$obj = json_decode($response);
			$token = $obj->{'id'};
			$bin = $obj->{'bin'};

			/* solicitud del pago */

			$curl = curl_init();

			curl_setopt_array($curl, array(
				//CURLOPT_URL => "https://developers.decidir.com/api/v2/payments", // ambiente test
				CURLOPT_URL => "https://live.decidir.com/api/v2/payments", // ambiente producción
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "{\"site_transaction_id\":\"$order_id\",\"token\":\"$token\",\"payment_method_id\":$tarjeta_tipo,\"bin\":\"$bin\",\"amount\":$total,\"currency\":\"ARS\",\"installments\":$cuotas,\"description\":\"\",\"payment_type\":\"single\",\"sub_payments\":[]}",
				CURLOPT_HTTPHEADER => array(
					//"apikey: 1b19bb47507c4a259ca22c12f78e881f", //private key test
					"apikey: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", //private key producción
					"cache-control: no-cache",
					"content-type: application/json"
				),
			));

			//var_dump($_REQUEST);			
			//wp_mail("info@pymesign.com", "Request Medifar", print_r($_REQUEST, true));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				//echo 'response pago: ' . $response;
				wp_mail("info@pymesign.com", "Response pago Medifar", $response);
			}

			/* end decidir ejemplo */

			//serializamos los datos de la compra
			$obj = json_decode($response);
			$status = $obj->{'status'};
			$amount = $obj->{'amount'};
			$total_financiado = $amount / 100;

			update_post_meta($order_id, 'total_financiado', esc_attr($total_financiado));

			if ($status == 'approved') {

				// Mark as on-hold (we're awaiting the payment)
				$order->update_status('completed', __('Se realizó el pago con tarjeta correctamente', 'wc-gateway-decidir'));

				// Reduce stock levels
				$order->reduce_order_stock();

				// Remove cart
				WC()->cart->empty_cart();

				// Return thankyou redirect
				return array(
					'result' 	=> 'success',
					'redirect'	=> $this->get_return_url($order)
				);
			} else {
				$order->update_status('cancelled', __('Cancelado', 'wc-gateway-decidir'));
				$redirect = $order->get_cancel_order_url();
			}

			wp_safe_redirect($redirect);
		}
	} // end \WC_Gateway_Decidir class
}
