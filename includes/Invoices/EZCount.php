<?php

namespace Pelecard\Invoices;

use Pelecard\Traits\Singleton;
use WC_Order;

class EZCount extends Base {

	use Singleton;

	/**
	 * @var string $provider
	 */
	public static $provider = 'ezcount';

	/**
	 * @var string[] $item_scheme
	 */
	protected static $item_scheme = [
		'details',
		'price',
		'amount',
	];

	/**
	 * EZCount constructor.
	 */
	public function __construct() {
		add_filter( 'wpg/invoices/' . self::$provider . '_formatted_item', [ $this, 'add_catalog_number' ], 10, 2 );

		parent::__construct();
	}

	/**
	 * @param array                  $item_data
	 * @param \WC_Order_Item_Product $item
	 *
	 * @return array
	 */
	public function add_catalog_number( array $item_data, \WC_Order_Item_Product $item ): array {
		$product = $item->get_product();
		$item_data['catalog_number'] = $product ? $product->get_sku() : null;

		return $item_data;
	}

	/**
	 * @param array     $args
	 * @param \WC_Order $order
	 *
	 * @return array
	 */
	public function get_checkout_args( array $args, WC_Order $order ): array {
		$args['EZcountParameters'] = $this->apply_checkout_filters( [
			'type' => $this->get_option( 'ezcount_doc_type' ),
			'api_key' => $this->get_option( 'ezcount_api_key' ),
			'api_email' => $this->get_option( 'ezcount_api_email' ),
			'developer_email' => $this->get_option( 'ezcount_dev_email' ),
			'transaction_id' => $order->get_id(),
			'description' => '',
			'ua_uuid' => $this->get_option( 'ezcount_uuid' ),
			'lang' => $this->get_option( 'ezcount_lang' ),
			'customer_name' => $order->get_formatted_billing_full_name(),
			'customer_address' => $order->get_billing_address_1(),
			'customer_phone' => $order->get_billing_phone(),
			'customer_email' => $order->get_billing_email(),
			'comment' => sprintf( __( 'Order #%s', 'woo-pelecard-gateway' ), $order->get_order_number() ),
			'email_text' => '',
			'dont_send_email' => false,
			'send_copy' => 'yes' === $this->get_option( 'ezcount_send_copy' ),
			'vat_type' => $this->get_option( 'ezcount_vat_type' ),
			'item' => $this->get_formatted_items( $order ),
			'forceItemsIntoNonItemsDocument' => 400 === (int) $this->get_option( 'ezcount_doc_type' ),
		], $order );

		return $args;
	}

	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public function get_admin_fields( array $fields ): array {
		$fields = array_merge( $fields, [
			'ezcount_title' => [
				'title' => __( 'EZCount', 'woo-pelecard-gateway' ),
				'type' => 'title',
			],
			'ezcount' => [
				'title' => __( 'Enable/Disable', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => '',
				'label' => __( 'Enable EZcount', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => false,
			],
			'ezcount_api_key' => [
				'title' => __( 'API Key', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'ezcount_uuid' => [
				'title' => __( 'Sub Account', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'ezcount_doc_type' => [
				'title' => __( 'Document Type', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 320,
				'desc_tip' => false,
				'options' => [
					'300' => __( 'Heshbonit Iska', 'woo-pelecard-gateway' ),
					'305' => __( 'Heshbonit Mas', 'woo-pelecard-gateway' ),
					'320' => __( 'Heshbonit Mas Kabala', 'woo-pelecard-gateway' ),
					'330' => __( 'Heshbonit Mas Zikui', 'woo-pelecard-gateway' ),
					'400' => __( 'Kabala', 'woo-pelecard-gateway' ),
					'405' => __( 'Kabala Al Trumot', 'woo-pelecard-gateway' ),
				],
			],
			'ezcount_vat_type' => [
				'title' => __( 'VAT Type', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'INC',
				'desc_tip' => false,
				'options' => [
					'INC' => __( 'Including VAT', 'woo-pelecard-gateway' ),
					'NON' => __( 'Without VAT', 'woo-pelecard-gateway' ),
				],
			],
			'ezcount_api_email' => [
				'title' => __( 'Business owner email', 'woo-pelecard-gateway' ),
				'type' => 'email',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'ezcount_dev_email' => [
				'title' => __( 'Developer Email', 'woo-pelecard-gateway' ),
				'type' => 'email',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'ezcount_send_copy' => [
				'title' => __( 'Send copy', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => 'Sends a copy to the email of the business owner',
				'label' => __( 'Enabled', 'woo-pelecard-gateway' ),
				'default' => 'yes',
				'desc_tip' => true,
			],
			'ezcount_lang' => [
				'title' => __( 'Document Language', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'he',
				'desc_tip' => false,
				'options' => [
					'he' => __( 'Hebrew', 'woo-pelecard-gateway' ),
					'en' => __( 'English', 'woo-pelecard-gateway' ),
				],
			],
		] );

		return $fields;
	}
}
