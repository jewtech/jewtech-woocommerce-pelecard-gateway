<?php

namespace Pelecard;

/**
 * Class Transaction
 */
class Transaction extends \WC_Data {

	const META_KEY = '_wpg_transaction';

	const ACTION_TYPE_J2 = '2';

	const ACTION_TYPE_J4 = '4';

	const ACTION_TYPE_J5 = '5';

	/**
	 * @var string $id
	 */
	protected $id = '';

	/**
	 * @var array $data
	 */
	protected $data = [
		'status_code' => '',
		'error_message' => '',
	];

	/**
	 * @var bool $validate
	 */
	protected $validate = true;

	/**
	 * Transaction constructor.
	 *
	 * @param string $transaction_id
	 */
	public function __construct( string $transaction_id = '' ) {
		parent::__construct();

		$this->set_id( $transaction_id );

		if ( ! empty( $this->get_id() ) ) {
			$this->set_data( $this->get_transaction() );
		}
	}

	/**
	 * @param int $order_id
	 *
	 * @return $this
	 */
	public function set_order_id( int $order_id ) {
		$this->add_meta_data( 'ParamX', $order_id, true );

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * @param $transaction_id
	 *
	 * @return $this|void
	 */
	public function set_id( $transaction_id ) {
		$this->id = $transaction_id;

		return $this;
	}

	/**
	 * @param array $data
	 *
	 * @return $this
	 */
	public function set_data( array $data ) {
		$this->set_props( [
			'status_code' => $data['StatusCode'] ?? '',
			'error_message' => $data['ErrorMessage'] ?? '',
		] );

		if ( ! empty( $data['ResultData'] ) ) {
			foreach ( $data['ResultData'] as $key => $value ) {
				$this->add_meta_data( $key, $value, true );
			}
		}

		if ( ! empty( $data['UserData'] ) ) {
			foreach ( $data['UserData'] as $key => $value ) {
				$this->add_meta_data( $key, $value, true );
			}
		}

		if ( ! empty( $data['EZCountData'] ) ) {
			foreach ( $data['EZCountData'] as $key => $value ) {
				$this->add_meta_data( $key, $value, true );
			}
		}

		if ( ! empty( $data['ICountData'] ) ) {
			foreach ( $data['ICountData'] as $key => $value ) {
				$this->add_meta_data( $key, $value, true );
			}
		}

		if ( ! empty( $data['TamalData'] ) ) {
			foreach ( $data['TamalData'] as $key => $value ) {
				$this->add_meta_data( $key, $value, true );
			}
		}

		if ( empty( $this->get_id() ) ) {
			$this->set_id( $this->get_meta( 'PelecardTransactionId' ) );
		}

		$this->set_object_read( true );

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_transaction(): array {
		$result = $this->is_uuid() ? Api::get_transaction_by_id( $this->get_id() ) : [];

		return is_wp_error( $result ) ? [] : $result;
	}

	/**
	 * @return bool
	 */
	public function is_uuid(): bool {
		$pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

		return (bool) preg_match( $pattern, $this->get_id() );
	}

	/**
	 * @param string $context
	 *
	 * @return int
	 */
	public function get_order_id( $context = 'view' ) {
		if ( $this->meta_exists( 'AdditionalDetailsParamX' ) ) {
			$order_id = $this->get_meta( 'AdditionalDetailsParamX', true, $context );
		} elseif ( $this->meta_exists( 'ParamX' ) ) {
			$order_id = $this->get_meta( 'ParamX', true, $context );
		}

		return (int) ( $order_id ?? 0 );
	}

	/**
	 * @param $data
	 *
	 * @return $this
	 */
	public function set_json_data( $data ) {
		if ( is_string( $data ) ) {
			$data = json_decode( $data, true );
		}

		$this->set_props( $data );

		if ( ! empty( $data['meta_data'] ) ) {
			$this->set_meta_data( $data['meta_data'] );
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function set_meta_data( $data ) {
		if ( empty( $data ) || ! is_array( $data ) ) {
			return;
		}

		$this->maybe_read_meta_data();

		foreach ( $data as $meta ) {
			$meta = (array) $meta;
			if ( isset( $meta['key'], $meta['value'] ) ) {
				$this->meta_data[] = new \WC_Meta_Data( [
					'key' => $meta['key'],
					'value' => $meta['value'],
				] );
			}
		}
	}

	/**
	 * @param bool $validate
	 *
	 * @return $this
	 */
	public function set_validate( bool $validate ) {
		$this->validate = $validate;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function needs_validation(): bool {
		return apply_filters( 'wpg/transaction/needs_validation', $this->validate, $this );
	}

	/**
	 * @param string $status_code
	 *
	 * @return $this
	 */
	public function set_status_code( string $status_code ) {
		$this->set_prop( 'status_code', $status_code );

		return $this;
	}

	/**
	 * @param string $error_message
	 *
	 * @return $this
	 */
	public function set_error_message( string $error_message ) {
		$this->set_prop( 'error_message', $error_message );

		return $this;
	}

	/**
	 * @param string $context
	 *
	 * @return mixed|null
	 */
	public function get_error_message( $context = 'view' ) {
		return $this->get_prop( 'error_message', $context );
	}

	/**
	 * @return bool
	 */
	public function validate(): bool {
		$confirmation_key = $this->get_meta( 'ConfirmationKey' );
		$debit_total = $this->get_meta( 'DebitTotal' );
		$unique_key = $this->get_unique_key();

		return Api::validate_by_unique_key( $confirmation_key, $debit_total, $unique_key );
	}

	/**
	 * @return false|string
	 */
	public function get_unique_key() {
		$order = $this->get_order();
		if ( $order ) {
			return $order->get_order_key();
		}

		return Gateway::instance()->get_user_nonce( $this->get_user_id() );
	}

	/**
	 * @return bool|\WC_Order|\WC_Order_Refund
	 */
	public function get_order() {
		$order_id = $this->get_order_id();

		return wc_get_order( $order_id );
	}

	public function save() {
		$order = $this->get_order();
		if ( ! $order || $this->exists( $order ) ) {
			return;
		}

		$order->add_meta_data( self::META_KEY, (string) $this );
		$order->save();

		do_action( 'wpg/transaction/after_save', $this, $order );
	}

	/**
	 * @return bool
	 */
	public function is_token_valid(): bool {
		return 0 < $this->get_user_id() && ! empty( $this->get_token() );
	}

	/**
	 * @param string $context
	 *
	 * @return int
	 */
	public function get_user_id( $context = 'view' ): int {
		return (int) $this->get_meta( 'UserData1', true, $context );
	}

	/**
	 * @param string $context
	 *
	 * @return string
	 */
	public function get_timeout_redirect_url( $context = 'view' ): string {
		return $this->get_meta( 'UserData2', true, $context );
	}

	/**
	 * @param \WC_Order $order
	 *
	 * @return bool
	 */
	public function exists( \WC_Order $order ) {
		$transactions = Order::instance()->get_transactions( $order );

		return array_reduce( $transactions, function( $carry, $transaction ) {
			return $carry || $transaction->get_id() === $this->get_id();
		}, false );
	}

	/**
	 * @return array
	 */
	public function flatten(): array {
		$flatten = [
			'StatusCode' => $this->get_status_code(),
			'ErrorMessage' => $this->get_error_message(),
		];

		foreach ( $this->get_meta_data() as $meta ) {
			$flatten[ $meta->get_data()['key'] ] = $meta->get_data()['value'];
		}

		return $flatten;
	}

	/**
	 * @param string $context
	 *
	 * @return array|mixed|string
	 */
	public function get_token( $context = 'view' ): string {
		return $this->get_meta( 'Token', true, $context );
	}

	/**
	 * @param string $context
	 *
	 * @return array|mixed|string
	 */
	public function get_debit_approve_number( $context = 'view' ): string {
		return $this->get_meta( 'DebitApproveNumber', true, $context );
	}

	/**
	 * @param \Pelecard\Gateway $gateway
	 *
	 * @return \WC_Payment_Token_CC
	 */
	public function get_token_object( Gateway $gateway ): \WC_Payment_Token_CC {
		$token = new \WC_Payment_Token_CC();
		$token->set_gateway_id( $gateway->id );
		$token->set_token( $this->get_token() );
		$token->set_last4( $this->get_last4() );
		$token->set_card_type( $this->get_card_type() );
		$token->set_expiry_year( $this->get_expiry_year() );
		$token->set_expiry_month( $this->get_expiry_month() );
		$token->set_user_id( $this->get_user_id() );

		return $token;
	}

	/**
	 * @return false|string
	 */
	public function get_last4() {
		return substr( $this->get_card_number(), -4 );
	}

	/**
	 * @param string $context
	 *
	 * @return string
	 */
	public function get_card_number( $context = 'view' ): string {
		return $this->get_meta( 'CreditCardNumber', true, $context );
	}

	/**
	 * @return string
	 */
	public function get_card_type(): string {
		$brand = $this->get_card_brand();
		$company = $this->get_card_company();

		return ( 0 < $brand )
			? $this->get_card_type_by_brand( $brand )
			: $this->get_card_type_by_company( $company );
	}

	/**
	 * @param string $context
	 *
	 * @return int
	 */
	public function get_card_brand( $context = 'view' ): int {
		return (int) $this->get_meta( 'CreditCardBrand', true, $context );
	}

	/**
	 * @param string $context
	 *
	 * @return int
	 */
	public function get_card_company( $context = 'view' ): int {
		return (int) $this->get_meta( 'CreditCardCompanyClearer', true, $context );
	}

	/**
	 * @param $brand
	 *
	 * @return mixed
	 */
	public function get_card_type_by_brand( $brand ) {
		$brands = apply_filters( 'wpg/card_brands', [
			1 => 'mastercard',
			2 => 'visa',
			3 => 'maestro',
			5 => 'isracard',
		] );

		return $brands[ $brand ] ?? $brand;
	}

	/**
	 * @param $company
	 *
	 * @return mixed
	 */
	public function get_card_type_by_company( $company ) {
		$companies = apply_filters( 'wpg/card_companies', [
			1 => 'isracard',
			2 => 'visa',
			3 => 'diners',
			4 => 'american express',
			6 => 'leumi card',
		] );

		return $companies[ $company ] ?? $company;
	}

	/**
	 * @return false|string
	 */
	public function get_expiry_year() {
		$expiry_year = substr( $this->get_card_expiry(), -2 );

		return date_create_from_format( 'y', $expiry_year )->format( 'Y' );
	}

	/**
	 * @param string $context
	 *
	 * @return array|mixed|string
	 */
	public function get_card_expiry( $context = 'view' ): string {
		return $this->get_meta( 'CreditCardExpDate', true, $context );
	}

	/**
	 * @return false|string
	 */
	public function get_expiry_month() {
		return substr( $this->get_card_expiry(), 0, 2 );
	}

	/**
	 * @param string $context
	 *
	 * @return int
	 */
	public function get_total_payments( $context = 'view' ): int {
		$total_payments = (int) $this->get_meta( 'TotalPayments', true, $context );

		return $total_payments ?: 1;
	}

	/**
	 * @return bool
	 */
	public function is_success(): bool {
		return '000' === $this->get_status_code();
	}

	/**
	 * @param string $context
	 *
	 * @return mixed|null
	 */
	public function get_status_code( $context = 'view' ): string {
		return $this->get_prop( 'status_code', $context );
	}

	/**
	 * @return bool
	 */
	public function is_timeout(): bool {
		return 301 === (int) $this->get_status_code();
	}

	/**
	 * @return bool
	 */
	public function is_3ds_failure(): bool {
		return 650 === (int) $this->get_status_code();
	}

	/**
	 * @return array
	 */
	public function get_3ds_params(): array {
		$params = array_filter( [
			$this->get_meta( 'Eci' ),
			$this->get_meta( 'XID' ),
			$this->get_meta( 'Cavv' ),
		] );

		return 3 === count( $params ) ? $params : [];
	}

	/**
	 * @param string $context
	 *
	 * @return string
	 */
	public function get_action_type( $context = 'view' ): string {
		return $this->get_meta( 'JParam', true, $context );
	}

	/**
	 * @param string $action_type
	 *
	 * @return bool
	 */
	public function is_action_type( string $action_type ): bool {
		return $action_type === $this->get_action_type();
	}
}
