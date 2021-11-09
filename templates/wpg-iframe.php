<?php
/**
 * The template for displaying WPG iframe.
 *
 * @var string $iframe_url
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/wpg-iframe.php
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="wpg-iframe-container">
	<iframe src="<?= esc_url( $iframe_url ); ?>" style="border: 0; width: 100%; height: 800px;"></iframe>
</div>
