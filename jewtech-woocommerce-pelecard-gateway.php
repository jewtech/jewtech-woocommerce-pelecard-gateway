<?php

/*
Plugin Name: Jewtech Woocommerce Pelecard Gateway
Plugin URI: https://github.com/jewtech/jewtech-woocommerce-pelecard-gateway
Description: Extends WooCommerce with Pelecard payment gateway. Extended by Jewtech.
Author: Natan Rona
Author URI: https://github.com/nathanrona
License: GP3 or higher
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
