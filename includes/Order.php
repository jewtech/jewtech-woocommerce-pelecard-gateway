<?php

namespace Pelecard;

use Pelecard\Traits\Singleton;
use WC_Order;

/**
 * Class Order
 */
class Order {

	use Singleton;

	/**
	 * Order constructor.
	 */
	private function __construct() {
		add_action( 'admin_init', [ $this, 'register_hooks' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 20 );
		add_action( 'woocommerce_order_item_add_action_buttons', [ $this, 'render_charge_button' ] );
	}

	public function add_meta_boxes( $post_type, $post ) {
		if ( 'shop_order' !== $post_type ) {
			return;
		}

		$order = wc_get_order( $post );
		$transactions = $this->get_transactions( $order );

		if ( empty( $transactions ) ) {
			return;
		}

		add_meta_box(
			'wpg-transactions',
			__( 'Transactions', 'woo-pelecard-gateway' ),
			[ $this, 'render_transactions_metabox' ],
			$post_type,
			'advanced',
			'default',
			[ 'transactions' => $transactions ]
		);
	}

	/**
	 * @param \WC_Order $order
	 *
	 * @return \Pelecard\Transaction[]
	 */
	public function get_transactions( WC_Order $order ) {
		$transactions = [];

		/**
		 * @var \WC_Meta_Data $meta
		 */
		foreach ( $order->get_meta( Transaction::META_KEY, false ) as $meta ) {
			$transactions[] = ( new Transaction() )->set_json_data( $meta->get_data()['value'] );
		}

		return $transactions;
	}

	/**
	 * @param \WP_Post $post
	 * @param array    $metabox
	 */
	public function render_transactions_metabox( \WP_Post $post, array $metabox ) {
		wc_get_template(
			'order/wpg-transactions.php',
			[
				'transactions' => $metabox['args']['transactions'],
			],
			null,
			Plugin::get_templates_path()
		);
	}

	/**
	 * @param \WC_Order $order
	 */
	public function render_charge_button( WC_Order $order ) {
		$is_chargeable = Gateway::is_order_chargeable( $order );
		if ( ! $is_chargeable || ! current_user_can( 'edit_shop_orders' ) ) {
			return;
		}

		echo sprintf( '<button type="button" class="button wpg-charge">%1$s</button>', esc_html__( 'Charge', 'woo-pelecard-gateway' ) );
	}

	public function enqueue_admin_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'wpg-order',
			Plugin::get_directory_url() . '/assets/js/admin/order' . $suffix . '.js',
			[ 'jquery', 'jquery-ui-accordion' ],
			Plugin::$version
		);

		wp_localize_script(
			'wpg-order',
			'wpg_i18n',
			[
				'do_charge' => __( 'Are you sure you wish to process this charge? This action cannot be undone.', 'woo-pelecard-gateway' ),
			]
		);
	}

	public function register_hooks() {
		add_action( 'wp_ajax_wpg_charge_order', [ Gateway::instance(), 'charge_by_order' ] );
	}

	/**
	 * Get order's 3DSecure params
	 *
	 * @param \WC_Order $order
	 *
	 * @return array
	 */
	public static function get_3ds_params( WC_Order $order ): array {
		$params = array_filter( [
			$order->get_meta( '_wpg_3ds_eci' ),
			$order->get_meta( '_wpg_3ds_xid' ),
			$order->get_meta( '_wpg_3ds_cavv' ),
		] );

		return 3 === count( $params ) ? $params : [];
	}
}
