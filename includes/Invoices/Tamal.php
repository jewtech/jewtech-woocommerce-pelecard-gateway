<?php

namespace Pelecard\Invoices;

use Pelecard\Traits\Singleton;
use WC_Order;

class Tamal extends Base {

	use Singleton;

	/**
	 * @var string $provider
	 */
	public static $provider = 'tamal';

	/**
	 * @var string[] $item_scheme
	 */
	protected static $item_scheme = [
		'Description',
		'Price',
		'Quantity',
	];

	/**
	 * @param array     $args
	 * @param \WC_Order $order
	 *
	 * @return array
	 */
	public function get_checkout_args( array $args, WC_Order $order ): array {
		$args['TamalInvoice'] = $this->apply_checkout_filters( [
			'InvoiceUserName' => $this->get_option( 'tamal_user' ),
			'InvoicePassword' => $this->get_option( 'tamal_password' ),
			'EsekNum' => $this->get_option( 'tamal_esek_num' ),
			'TypeCode' => $this->get_option( 'tamal_doc_type' ),
			'PrintLanguage' => $this->get_option( 'tamal_lang' ),
			'ClientNumber' => 200000,
			'ClientName' => $order->get_formatted_billing_full_name(),
			'ClientAddress' => $order->get_billing_address_1(),
			'ClientCity' => $order->get_billing_city(),
			'EmailAddress' => $order->get_billing_email(),
			'NikuyBamakorSum' => 0,
			'MaamRate' => 'mursh' === $this->get_option( 'tamal_osek' ) ? 999 : 0,
			'DocDetail' => sprintf( __( 'Order #%s', 'woo-pelecard-gateway' ), $order->get_order_number() ),
			'ToSign' => 1,
			'DocRemark' => $this->get_option( 'tamal_doc_remark' ),
			'ProductsList' => $this->get_formatted_items( $order ),
			'DiscountAmount' => '',
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
			'tamal_title' => [
				'title' => __( 'Tamal', 'woo-pelecard-gateway' ),
				'type' => 'title',
			],
			'tamal' => [
				'title' => __( 'Enable/Disable', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => '',
				'label' => __( 'Enable Tamal', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => false,
			],
			'tamal_user' => [
				'title' => __( 'Username', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'tamal_password' => [
				'title' => __( 'Password', 'woo-pelecard-gateway' ),
				'type' => 'password',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'tamal_esek_num' => [
				'title' => __( 'Esek number', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'tamal_osek' => [
				'title' => __( 'Osek Type', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 0,
				'desc_tip' => false,
				'options' => [
					'mursh' => __( 'Osek Mursh', 'woo-pelecard-gateway' ),
					'patur' => __( 'Osek Patur', 'woo-pelecard-gateway' ),
					'amuta' => __( 'Amuta', 'woo-pelecard-gateway' ),
				],
			],
			'tamal_doc_type' => [
				'title' => __( 'Document Type', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 320,
				'desc_tip' => false,
				'options' => [
					'100' => __( 'Hazmana', 'woo-pelecard-gateway' ),
					'300' => __( 'Heshbonit Iska', 'woo-pelecard-gateway' ),
					'305' => __( 'Heshbonit Mas', 'woo-pelecard-gateway' ),
					'320' => __( 'Heshbonit Mas Kabala', 'woo-pelecard-gateway' ),
					'330' => __( 'Heshbonit Mas Zikui', 'woo-pelecard-gateway' ),
					'400' => __( 'Kabala', 'woo-pelecard-gateway' ),
					'405' => __( 'Kabala Al Trumot', 'woo-pelecard-gateway' ),
					'10100' => __( 'Hazaat Mehir', 'woo-pelecard-gateway' ),
					'10301' => __( 'Heshbon Iska', 'woo-pelecard-gateway' ),
				],
			],
			'tamal_lang' => [
				'title' => __( 'Document Language', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 0,
				'desc_tip' => false,
				'options' => [
					'0' => __( 'Hebrew', 'woo-pelecard-gateway' ),
					'1' => __( 'English', 'woo-pelecard-gateway' ),
				],
			],
			'tamal_doc_remark' => [
				'title' => __( 'Document Remark', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
		] );

		return $fields;
	}
}
