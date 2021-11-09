<?php
/**
 * Add transactions metabox
 *
 * @var \Pelecard\Transaction[] $transactions
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/wpg-transactions.php
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly
?>

<style>
	#wpg-transactions h3 a {
		color: #0073aa;
		padding-right: .5em;
	}

	#wpg-transactions h3 a:hover {
		text-decoration: underline;
	}
</style>

<table id="wpg-transactions" style="width: 100%;">
	<tbody>
	<?php foreach ( $transactions as $transaction ) : ?>
		<tr>
			<td>
				<h3>
					<?php
					echo $transaction->get_id();

					$invoice = $transaction->get_meta('InvoiceLink');
					if ( ! empty( $invoice ) ) {
						printf(
							'<a href="%2$s" class="alignright" target="_blank">%1$s</a>',
							__( 'Get Invoice', 'woo-pelecard-gateway' ),
							$invoice
						);
					}
					?>
				</h3>
				<div>
					<table class="wp-list-table widefat fixed striped">
						<thead>
						<tr>
							<th scope="col" class="manage-column"><?php _e( 'Parameter', 'woo-pelecard-gateway' ); ?></th>
							<th scope="col" class="manage-column"><?php _e( 'Value', 'woo-pelecard-gateway' ); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ( $transaction->get_meta_data() as $meta ) : ?>
							<tr>
								<td><span><?= $meta->get_data()['key']; ?></span></td>
								<td><span><?= $meta->get_data()['value']; ?></span></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
