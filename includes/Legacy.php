<?php

namespace Pelecard;

use Pelecard\Traits\Singleton;

/**
 * Class Legacy
 */
class Legacy {

	use Singleton;

	const LEGACY_TRANSACTION_META_KEY = '_transaction_data';

	/**
	 * Legacy constructor.
	 */
	private function __construct() {
		add_action( 'admin_menu', [ $this, 'upgrade_all' ] );
		add_action( 'wpg/transaction/after_save', [ $this, 'legacy_transaction_data' ], 10, 2 );
	}

	public function upgrade_all() {
		if ( ! $this->has_upgrade() ) {
			return;
		}

		add_action( 'admin_notices', [ $this, 'upgrade_notice' ] );

		$this->upgrade_14_settings();

		$this->update_db_version( Plugin::$version );
	}

	/**
	 * @return bool
	 */
	public function has_upgrade(): bool {
		$db_version = $this->get_db_version();

		if ( empty( $db_version ) ) {
			return true;
		}

		return version_compare( $db_version, Plugin::$version, '<' );
	}

	/**
	 * @return string
	 */
	public function get_db_version(): string {
		return get_option( 'wpg_version' );
	}

	public function upgrade_14_settings() {
		$gateway = Gateway::instance();

		$migrate = json_decode(
			file_get_contents( Plugin::get_migrations_path( 'migrate_14.json' ) ),
			true
		);

		foreach ( $migrate as $old => $new ) {
			$value = $gateway->get_option( $old );

			if ( 'payment_range' === $new ) {
				$gateway->settings[ $new ] = $this->get_payment_range( $value );
			} elseif ( ! empty( $value ) ) {
				$gateway->settings[ $new ] = $value;
			}

			if ( isset( $gateway->settings[ $old ] ) ) {
				unset( $gateway->settings[ $old ] );
			}
		}

		update_option( $gateway->get_option_key(), $gateway->settings );
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	public function get_payment_range( $value ) {
		$new_value = [];

		if ( empty( $value ) ) {
			return $new_value;
		}

		foreach ( (array) $value as $k => $v ) {
			$new_value[ $k ] = [
				'min_cart' => $v['cart']['min'],
				'max_cart' => $v['cart']['max'],
				'min_payments' => $v['min'],
				'max_payments' => $v['max'],
			];
		}

		return $new_value;
	}

	/**
	 * @param string $version
	 */
	public function update_db_version( $version = '' ) {
		update_option( 'wpg_version', $version );
	}

	/**
	 * @param \Pelecard\Transaction $transaction
	 * @param \WC_Order             $order
	 */
	public function legacy_transaction_data( Transaction $transaction, \WC_Order $order ) {
		$order->add_meta_data( self::LEGACY_TRANSACTION_META_KEY, $transaction->flatten() );
		$order->save_meta_data();
	}

	public function upgrade_notice() {
		$message = esc_html__( 'Woo Pelecard Gateway has upgraded your database.', 'woo-pelecard-gateway' );
		$html_message = sprintf( '<div class="notice notice-warning">%s</div>', wpautop( $message ) );
		echo wp_kses_post( $html_message );
	}
}
