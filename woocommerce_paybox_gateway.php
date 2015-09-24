<?php
	/**
	 * Plugin Name: WooCommerce Paybox Payment Gateway
	 * Plugin URI: http://www.openboutique.fr/
	 * Description: Gateway e-commerce pour Paybox.
	 * Version: 0.4.7
	 * Author: SWO(Castelis), JLA(Castelis)
	 * Author URI: http://www.openboutique.fr/
	 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 * Text Domain: openboutique_paybox_gateway
	 *
	 * @package WordPress
	 * @author SWO(Castelis)
	 * @since 0.1.0
	 */

	if(!defined('ABSPATH'))
		exit;

	function activate_paybox_gateway()
	{
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if( !is_plugin_active('woocommerce/woocommerce.php') )
		{
			_e('Le plugin WooCommerce doit être activé pour l\'activation de l\'extension', TEXT_DOMAIN);
			exit;
		}
		if( !class_exists('WC_Payment_Gateway') )
		{
			_e('Une erreur est survenue concernant WooCommerce : Les méthodes de paiement semblent introuvables', TEXT_DOMAIN);
			exit;
		}
	}
	register_activation_hook(__FILE__, 'activate_paybox_gateway');
	add_action('plugins_loaded', 'woocommerce_paybox_init', 0);

	function woocommerce_paybox_init()
	{
		if( class_exists('WC_Payment_Gateway') )
		{
			include_once( plugin_dir_path( __FILE__ ).'woocommerce_paybox_gateway.class.php' );
			include_once( plugin_dir_path( __FILE__ ).'shortcode_woocommerce_paybox_gateway.php' );
		} else
			exit;

		DEFINE('PLUGIN_DIR', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
		DEFINE('OB_VERSION', '0.4.7');
		DEFINE('THANKS_SHORTCODE', 'woocommerce_paybox_gateway_thanks');
		DEFINE('TEXT_DOMAIN', 'openboutique_paybox_gateway');

		// Chargement des traductions
		load_plugin_textdomain(TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)).'/languages/');

		add_shortcode( THANKS_SHORTCODE, 'WC_Shortcode_Paybox_Thankyou::get' );
		add_filter('woocommerce_payment_gateways', 'add_paybox_commerce_gateway');
		add_action('init', 'woocommerce_paybox_check_response');
	}

	/*
	 * Ajout de la "gateway" Paybox à woocommerce
	 */
	function add_paybox_commerce_gateway($methods)
	{
		$methods[] = 'WC_Paybox';
		return $methods;
	}
?>