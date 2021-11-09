<?php

namespace Pelecard;

/**
 * Class Settings
 */
class Settings {

	/**
	 * @return array
	 */
	public static function get_admin_fields(): array {
		$settings = [
			'general_title' => [
				'title' => __( 'General', 'woo-pelecard-gateway' ),
				'type' => 'title',
			],
			'enabled' => [
				'title' => __( 'Enable/Disable', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'label' => __( 'Enable Pelecard', 'woo-pelecard-gateway' ),
				'default' => 'yes',
			],
			'language' => [
				'title' => __( 'IFrame Language', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'EN',
				'desc_tip' => false,
				'options' => [
					'HE' => __( 'Hebrew', 'woo-pelecard-gateway' ),
					'EN' => __( 'English', 'woo-pelecard-gateway' ),
					'RU' => __( 'Russian', 'woo-pelecard-gateway' ),
				],
			],
			'title' => [
				'title' => __( 'Title', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-pelecard-gateway' ),
				'default' => __( 'Pay by credit card', 'woo-pelecard-gateway' ),
				'desc_tip' => true,
			],
			'description' => [
				'title' => __( 'Description', 'woo-pelecard-gateway' ),
				'type' => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your checkout.', 'woo-pelecard-gateway' ),
				'default' => __( 'Pay by credit card', 'woo-pelecard-gateway' ),
				'desc_tip' => true,
			],
			'order_button_text' => [
				'title' => __( 'Order Button Text', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => __( 'Set if the place order button should be renamed on selection.', 'woo-pelecard-gateway' ),
				'default' => __( 'Pay by credit card', 'woo-pelecard-gateway' ),
				'desc_tip' => true,
			],
			'icon' => [
				'title' => __( 'Icon', 'woo-pelecard-gateway' ),
				'type' => 'url',
				'description' => __( 'This controls the gateway icon which the user sees during checkout.', 'woo-pelecard-gateway' ),
				'default' => '',
				'desc_tip' => true,
			],
			'saved_cards' => [
				'title' => __( 'Saved Cards', 'woo-pelecard-gateway' ),
				'label' => __( 'Enable Payment via Saved Cards', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => __( 'If enabled, users will be able to pay with a saved card during checkout.', 'woo-pelecard-gateway' ),
				'default' => 'yes',
				'desc_tip' => true,
			],
			'upay' => [
				'title' => __( 'Upay', 'woo-pelecard-gateway' ),
				'label' => __( 'Enable Upay', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => __( 'If enabled, transactions will not be validated.', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => true,
			],
			'timeout_action' => [
				'title' => __( 'Timeout Action', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => __( 'Where would the user be redirected to, in case the iframe has timed-out.', 'woo-pelecard-gateway' ),
				'default' => 'cancel',
				'desc_tip' => true,
				'options' => [
					'cancel' => __( 'Cancel Order', 'woo-pelecard-gateway' ),
					'checkout' => __( 'Return to Checkout', 'woo-pelecard-gateway' ),
				],
			],
			'terminal_title' => [
				'title' => __( 'Terminal', 'woo-pelecard-gateway' ),
				'type' => 'title',
			],
			'terminal' => [
				'title' => __( 'Terminal', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'username' => [
				'title' => __( 'Username', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'password' => [
				'title' => __( 'Password', 'woo-pelecard-gateway' ),
				'type' => 'password',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'action_type' => [
				'title' => __( 'Action Type', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'J4',
				'desc_tip' => false,
				'options' => [
					'J2' => __( 'J2', 'woo-pelecard-gateway' ),
					'J4' => __( 'J4', 'woo-pelecard-gateway' ),
					'J5' => __( 'J5', 'woo-pelecard-gateway' ),
				],
			],
			'hook' => [
				'title' => __( 'Hook Terminal', 'woo-pelecard-gateway' ),
				'type' => 'title',
				'description' => __( 'Usually A second terminal is required to perform actions such as tokenizations, refunds, etc.', 'woo-pelecard-gateway' ),
				'default' => '',
				'desc_tip' => false,
			],
			'hook_terminal' => [
				'title' => __( 'Terminal', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'hook_username' => [
				'title' => __( 'Username', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'hook_password' => [
				'title' => __( 'Password', 'woo-pelecard-gateway' ),
				'type' => 'password',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'payments_title' => [
				'title' => __( 'Payments', 'woo-pelecard-gateway' ),
				'type' => 'title',
			],
			'min_payments' => [
				'title' => __( 'Min Payments', 'woo-pelecard-gateway' ),
				'type' => 'number',
				'description' => __( 'The amount of minimum payments.', 'woo-pelecard-gateway' ),
				'default' => '1',
				'desc_tip' => true,
				'custom_attributes' => [ 'min' => 1, 'required' => 'required' ],
			],
			'max_payments' => [
				'title' => __( 'Max Payments', 'woo-pelecard-gateway' ),
				'type' => 'number',
				'description' => __( 'The amount of maximum payments.', 'woo-pelecard-gateway' ),
				'default' => '12',
				'desc_tip' => true,
				'custom_attributes' => [ 'min' => 1, 'required' => 'required' ],
			],
			'min_credit' => [
				'title' => __( 'Min Payments For Credit', 'woo-pelecard-gateway' ),
				'type' => 'number',
				'description' => __( 'The amount of minimum payments required to define a credit transaction.', 'woo-pelecard-gateway' ),
				'default' => '13',
				'desc_tip' => true,
				'custom_attributes' => [ 'min' => 1, 'required' => 'required' ],
			],
			'payment_range' => [
				'type' => 'payment_range',
			],
			'fields_title' => [
				'title' => __( 'Fields', 'woo-pelecard-gateway' ),
				'type' => 'title',
			],
			'first_payment' => [
				'title' => __( 'First Payment', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => __( 'The initial amount should be less than the amount of the transaction.', 'woo-pelecard-gateway' ),
				'default' => 'auto',
				'desc_tip' => false,
				'options' => [
					'auto' => __( 'Auto', 'woo-pelecard-gateway' ),
					'manual' => __( 'Manual', 'woo-pelecard-gateway' ),
				],
			],
			'card_holder_name' => [
				'title' => __( 'Card Holder Name', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'hide',
				'desc_tip' => false,
				'options' => [
					'hide' => __( 'Disabled', 'woo-pelecard-gateway' ),
					'must' => __( 'Required', 'woo-pelecard-gateway' ),
					'optional' => __( 'Optional', 'woo-pelecard-gateway' ),
				],
			],
			'customer_id_field' => [
				'title' => __( 'Customer Id Field', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'hide',
				'desc_tip' => false,
				'options' => [
					'hide' => __( 'Disabled', 'woo-pelecard-gateway' ),
					'must' => __( 'Required', 'woo-pelecard-gateway' ),
					'optional' => __( 'Optional', 'woo-pelecard-gateway' ),
				],
			],
			'cvv2_field' => [
				'title' => __( 'Cvv2 Field', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'hide',
				'desc_tip' => false,
				'options' => [
					'hide' => __( 'Disabled', 'woo-pelecard-gateway' ),
					'must' => __( 'Required', 'woo-pelecard-gateway' ),
					'optional' => __( 'Optional', 'woo-pelecard-gateway' ),
				],
			],
			'email_field' => [
				'title' => __( 'Email Field', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'hide',
				'desc_tip' => false,
				'options' => [
					'hide' => __( 'Disabled', 'woo-pelecard-gateway' ),
					'must' => __( 'Required', 'woo-pelecard-gateway' ),
					'optional' => __( 'Optional', 'woo-pelecard-gateway' ),
					'value' => __( 'Required with value', 'woo-pelecard-gateway' ),
				],
			],
			'tel_field' => [
				'title' => __( 'Tel Field', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'hide',
				'desc_tip' => false,
				'options' => [
					'hide' => __( 'Disabled', 'woo-pelecard-gateway' ),
					'must' => __( 'Required', 'woo-pelecard-gateway' ),
					'optional' => __( 'Optional', 'woo-pelecard-gateway' ),
					'value' => __( 'Required with value', 'woo-pelecard-gateway' ),
				],
			],
			'split_cc_number' => [
				'class' => 'pelecard-tab',
				'title' => __( 'Split CC Number', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => __( 'Card field is divided into 4 groups of 4 numbers.', 'woo-pelecard-gateway' ),
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => true,
			],
			'cancel_button' => [
				'class' => 'pelecard-tab',
				'title' => __( 'Cancel Button', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => __( 'Cancel the order and redirect the buyer back to basket', 'woo-pelecard-gateway' ),
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => true,
			],
			'free_total' => [
				'class' => 'pelecard-tab',
				'title' => __( 'Free Total', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => __( 'Editable field will be displayed next to the original amount field, and the customer can add the desired amount.', 'woo-pelecard-gateway' ),
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => true,
			],
			'confirmation_cb' => [
				'title' => __( 'Confirmation CheckBox', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'false',
				'desc_tip' => false,
				'options' => [
					'false' => __( 'Disabled', 'woo-pelecard-gateway' ),
					'true' => __( 'Enabled', 'woo-pelecard-gateway' ),
					'checked' => __( 'Checked', 'woo-pelecard-gateway' ),
				],
			],
			'confirmation_text' => [
				'title' => __( 'Confirmation Text', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => __( 'Free text presented to the customer when confirmation box is enabled.', 'woo-pelecard-gateway' ),
				'default' => '',
				'desc_tip' => true,
			],
			'confirmation_url' => [
				'title' => __( 'Confirmation Link', 'woo-pelecard-gateway' ),
				'type' => 'url',
				'placeholder' => 'http://',
				'description' => __( 'HyperLink address of Confirmation Text.', 'woo-pelecard-gateway' ),
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => '',
				'desc_tip' => true,
			],
			'top_text' => [
				'title' => __( 'Top Text', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => true,
			],
			'bottom_text' => [
				'title' => __( 'Bottom Text', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => true,
			],
			'supported_cards' => [
				'title' => __( 'Supported Cards', 'woo-pelecard-gateway' ),
				'type' => 'multiselect',
				'class' => 'wc-enhanced-select',
				'description' => __( 'Display logos of the cards supported by the system.', 'woo-pelecard-gateway' ),
				'desc_tip' => false,
				'options' => [
					'Amex' => __( 'American Express', 'woo-pelecard-gateway' ),
					'Diners' => __( 'Diners', 'woo-pelecard-gateway' ),
					'Isra' => __( 'Isracard', 'woo-pelecard-gateway' ),
					'Master' => __( 'Mastercard', 'woo-pelecard-gateway' ),
					'Visa' => __( 'Visa', 'woo-pelecard-gateway' ),
				],
			],
			'set_focus' => [
				'title' => __( 'Focus on field', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'desc_tip' => false,
				'options' => [
					'' => __( 'Default (none)', 'woo-pelecard-gateway' ),
					'CC' => __( 'Card Number', 'woo-pelecard-gateway' ),
					'CCH' => __( 'Card Holder Name', 'woo-pelecard-gateway' ),
				],
			],
			'logo_url' => [
				'title' => __( 'Logo URL', 'woo-pelecard-gateway' ),
				'type' => 'url',
				'placeholder' => 'https://',
				'description' => __( 'Link to customers logo file.', 'woo-pelecard-gateway' ),
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => '',
				'desc_tip' => true,
			],
			'css_url' => [
				'title' => __( 'Css URL', 'woo-pelecard-gateway' ),
				'type' => 'url',
				'placeholder' => 'https://',
				'description' => __( 'CSS file link for custom design implementation.', 'woo-pelecard-gateway' ),
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => '',
				'desc_tip' => true,
			],
			'hidden_pelecard_logo' => [
				'title' => __( 'Pelecard Logo', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => __( 'Show / hide pelecard logo.', 'woo-pelecard-gateway' ),
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => true,
			],
		];

		return apply_filters( 'wpg/settings/admin_fields', $settings );
	}

	/**
	 * @return bool
	 */
	public static function is_settings_page(): bool {
		global $current_tab;

		return 'checkout' === $current_tab && Gateway::instance()->id === ( $_GET['section'] ?? '' );
	}

	/**
	 * @param string            $field
	 * @param \Pelecard\Gateway $gateway
	 *
	 * @return string
	 */
	public static function generate_payment_range_html( string $field, Gateway $gateway ): string {
		ob_start();

		$field_key = $gateway->get_field_key( $field );
		$ranges = array_filter( (array) $gateway->get_option( $field, [] ) );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Custom Payments', 'woo-pelecard-gateway' ); ?>:</th>
			<td class="forminp" id="wpg_payment_range">
				<div class="wc_input_table_wrapper">
					<table class="widefat wc_input_table sortable" cellspacing="0">
						<thead>
						<tr>
							<th class="sort">&nbsp;</th>
							<th><?php _e( 'Min Cart', 'woo-pelecard-gateway' ); ?></th>
							<th><?php _e( 'Max Cart', 'woo-pelecard-gateway' ); ?></th>
							<th><?php _e( 'Min Payments', 'woo-pelecard-gateway' ); ?></th>
							<th><?php _e( 'Max Payments', 'woo-pelecard-gateway' ); ?></th>
						</tr>
						</thead>
						<tbody class="ranges">
						<?php foreach ( $ranges as $i => $range ): ?>
							<tr class="range">
								<td class="sort"></td>
								<td>
									<input
										type="number"
										value="<?= esc_attr( $range['min_cart'] ); ?>"
										name="<?= $field_key . '[' . $i . '][min_cart]'; ?>"
										step="0.1"
										min="1"
										required
									/>
								</td>
								<td>
									<input
										type="number"
										value="<?= esc_attr( $range['max_cart'] ); ?>"
										name="<?= $field_key . '[' . $i . '][max_cart]'; ?>"
										step="0.1"
										min="1"
										required
									/>
								</td>
								<td>
									<input
										type="number"
										value="<?= esc_attr( $range['min_payments'] ); ?>"
										name="<?= $field_key . '[' . $i . '][min_payments]'; ?>"
										step="1"
										min="1"
										required
									/>
								</td>
								<td>
									<input
										type="number"
										value="<?= esc_attr( $range['max_payments'] ); ?>"
										name="<?= $field_key . '[' . $i . '][max_payments]'; ?>"
										step="1"
										min="1"
										required
									/>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
						<tfoot>
						<tr>
							<th colspan="7">
								<a href="#" class="add button"><?php _e( '+ Add row', 'woo-pelecard-gateway' ); ?></a>
								<a href="#" class="remove_rows button"><?php _e( 'Remove selected row(s)', 'woo-pelecard-gateway' ); ?></a>
							</th>
						</tr>
						</tfoot>
					</table>
				</div>
				<script type="text/javascript">
					jQuery( function() {
						var $container = jQuery( '#wpg_payment_range' );

						$container.on( 'click', 'a.add', function() {
							var size = $container.find( 'tbody .range' ).length;
							var field = '<?= $field_key; ?>[' + size + ']';

							jQuery( '<tr class="range">\
									<td class="sort"></td>\
									<td><input type="number" name="' + field + '[min_cart]" step="0.1" min="1" required /></td>\
									<td><input type="number" name="' + field + '[max_cart]" step="0.1" min="1" required /></td>\
									<td><input type="number" name="' + field + '[min_payments]" step="1" min="1" required /></td>\
									<td><input type="number" name="' + field + '[max_payments]" step="1" min="1" required /></td>\
								</tr>' ).appendTo( $container.find( 'tbody' ) );

							return false;
						} );
					} );
				</script>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}
}
