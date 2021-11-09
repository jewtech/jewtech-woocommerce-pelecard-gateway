<?php

/*
Plugin Name: Jewtech Woocommerce Pelecard Gateway
Plugin URI: http://URI_Of
Description: A brief description of the Plugin.
Version: 1.0
Author: user3
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/


/**
 * Plugin Name: Woo Pelecard Gateway
 * Plugin URI: https://wordpress.org/plugins/woo-pelecard-gateway/
 * Description: Extends WooCommerce with Pelecard payment gateway.
 * Version: 1.4.8
 * Author: Ido Friedlander
 * Author URI: https://profiles.wordpress.org/idofri/
 * Text Domain: woo-pelecard-gateway
 * Requires at least: 5.5
 * Requires PHP: 7.0
 *
 * WC requires at least: 3.0
 * WC tested up to: 5.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WPG_FILE' ) ) {
	define( 'WPG_FILE', __FILE__ );
}

add_action( 'plugins_loaded', 'wpg_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, '7.0', '>=' ) ) {
	add_action( 'admin_notices', 'wpg_fail_php_version' );
} else {
	require_once __DIR__ . '/vendor/autoload.php';

	\Pelecard\Plugin::instance();
}

function wpg_load_plugin_textdomain() {
	load_plugin_textdomain( 'woo-pelecard-gateway' );
}

function wpg_fail_php_version() {
	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'Woo Pelecard Gateway requires PHP version %s+, plugin is currently NOT RUNNING.', 'woo-pelecard-gateway' ), '7.0' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
