<?php
/**
 * Add payments fieldset
 *
 * @var int                  $min_credit
 * @var array                $payments
 * @var \Pelecard\Gateway    $gateway
 * @var \WC_Payment_Token_CC $token
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/wpg-total-payments.php.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly
?>
<fieldset class="form-row woocommerce-TotalPayments" style="display: none;">
	<label class="screen-reader-text" for="wc-pelecard-number-of-payments">
		<?php _e( 'Number of payments', 'woo-pelecard-gateway' ); ?>
	</label>
	<select name="wc-<?= $gateway->id; ?>-total-payments[<?= $token->get_id(); ?>]" id="wc-pelecard-number-of-payments">
		<option value=""><?php _e( 'Number of payments', 'woo-pelecard-gateway' ); ?></option>
		<?php foreach ( $payments as $payment ) : ?>
			<option value="<?= esc_attr( $payment ); ?>">
				<?= ( $min_credit <= $payment ) ? sprintf( __( '%s (Credit)', 'woo-pelecard-gateway' ), $payment ) : $payment; ?>
			</option>
		<?php endforeach; ?>
	</select>
</fieldset>
