<?php

namespace Pelecard\Invoices;

use Pelecard\Gateway;
use WC_Order;

/**
 * Class Base
 */
abstract class Base {

	/**
	 * @var null|string $provider
	 */
	public static $provider;

	/**
	 * Base constructor.
	 */
	protected function __construct() {
		add_action( 'plugins_loaded', [ $this, 'register_hooks' ] );
		add_filter( 'wpg/settings/admin_fields', [ $this, 'get_admin_fields' ], 20 );
	}

	public function register_hooks() {
		if ( $this->is_enabled() ) {
			add_filter( 'wpg/checkout/rest_args', [ $this, 'maybe_add_invoice_args' ], 10, 3 );
			add_filter( 'wpg/checkout/iframe_args', [ $this, 'maybe_add_invoice_args' ], 10, 3 );
		}
	}

	/**
	 * @return bool
	 */
	public function is_enabled(): bool {
		return 'yes' === $this->get_option( static::$provider );
	}

	/**
	 * @param string $key
	 * @param null   $empty_value
	 *
	 * @return mixed|string|void
	 */
	public function get_option( string $key, $empty_value = null ) {
		return Gateway::instance()->get_option( $key, $empty_value );
	}

	/**
	 * @param \WC_Order $order
	 *
	 * @return array
	 */
	public function get_formatted_items( WC_Order $order ): array {
		$items = [];

		list( $name, $total, $quantity ) = static::$item_scheme;

		/**
		 * @var \WC_Order_Item_Product $item
		 */
		foreach ( $order->get_items() as $item ) {
			$total_with_tax = (
				(float) $item->get_total() + (float) $item->get_total_tax()
			) / $item->get_quantity();

			$items[] = apply_filters( 'wpg/invoices/' . static::$provider . '_formatted_item', [
				$name => $item->get_name(),
				$total => $total_with_tax * 100,
				$quantity => $item->get_quantity(),
			], $item );
		}

		$shipping_total = (float) $order->get_shipping_total();
		if ( 0 < $shipping_total ) {
			$items[] = [
				$name => $order->get_shipping_method(),
				$total => $shipping_total * 100,
				$quantity => 1,
			];
		}

		return apply_filters( 'wpg/invoices/' . static::$provider . '_formatted_items', $items, $order );
	}

	/**
	 * @param array     $args
	 * @param \WC_Order $order
	 *
	 * @return array
	 */
	public function apply_checkout_filters( array $args, WC_Order $order ): array {
		return apply_filters( 'wpg/invoices/' . static::$provider . '_checkout_args', $args, $order );
	}

    /**
     * @param array             $args
     * @param \WC_Order         $order
     * @param \Pelecard\Gateway $gateway
     *
     * @return array
     */
	public function maybe_add_invoice_args( array $args, WC_Order $order, Gateway $gateway ) {
        if ( 'J4' !== $gateway->get_action_type() ) {
            return $args;
        }
        return $this->get_checkout_args( $args, $order );
    }

	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	abstract public function get_admin_fields( array $fields ): array;

	/**
	 * @param array     $args
	 * @param \WC_Order $order
	 *
	 * @return array
	 */
	abstract public function get_checkout_args( array $args, WC_Order $order ): array;
}
