<?php

namespace Pelecard\Invoices;

use Pelecard\Traits\Singleton;
use WC_Order;

class ICount extends Base {

	use Singleton;

	/**
	 * @var string $provider
	 */
	public static $provider = 'icount';

	/**
	 * @var string[] $item_scheme
	 */
	protected static $item_scheme = [
		'description',
		'unitprice_incvat',
		'quantity',
	];

	/**
	 * @param array     $args
	 * @param \WC_Order $order
	 *
	 * @return array
	 */
	public function get_checkout_args( array $args, WC_Order $order ): array {
		$args['ICountInvoice'] = $this->apply_checkout_filters( [
			'docType' => $this->get_option( 'icount_doc_type' ),
			'cid' => $this->get_option( 'icount_cid' ),
			'user' => $this->get_option( 'icount_user' ),
			'pass' => $this->get_option( 'icount_password' ),
			'client_name' => $order->get_formatted_billing_full_name(),
			'email_to' => $order->get_billing_email(),
			'send_email' => 1,
			'email_lang' => $this->get_option( 'icount_email_lang' ),
			'doc_title' => sprintf( __( 'Order #%s', 'woo-pelecard-gateway' ), $order->get_order_number() ),
			'hwc' => $this->get_option( 'icount_hwc' ),
			'items' => $this->get_formatted_items( $order ),
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
			'icount_title' => [
				'title' => __( 'ICount', 'woo-pelecard-gateway' ),
				'type' => 'title',
			],
			'icount' => [
				'title' => __( 'Enable/Disable', 'woo-pelecard-gateway' ),
				'type' => 'checkbox',
				'description' => '',
				'label' => __( 'Enable iCount', 'woo-pelecard-gateway' ),
				'default' => 'no',
				'desc_tip' => false,
			],
			'icount_cid' => [
				'title' => __( 'Company ID', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'icount_user' => [
				'title' => __( 'Username', 'woo-pelecard-gateway' ),
				'type' => 'text',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'icount_password' => [
				'title' => __( 'Password', 'woo-pelecard-gateway' ),
				'type' => 'password',
				'description' => '',
				'default' => '',
				'desc_tip' => false,
			],
			'icount_doc_type' => [
				'title' => __( 'Document Type', 'woo-pelecard-gateway' ),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'description' => '',
				'default' => 'invrec',
				'desc_tip' => false,
				'options' => [
					'invrec' => __( 'Heshbonit Mas Kabala', 'woo-pelecard-gateway' ),
					'receipt' => __( 'Kabala', 'woo-pelecard-gateway' ),
					'trec' => __( 'Truma', 'woo-pelecard-gateway' ),
					'invoice' => __( 'Heshbonit Mas', 'woo-pelecard-gateway' ),
				],
			],
			'icount_email_lang' => [
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
			'icount_hwc' => [
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
