<?php

namespace Pelecard;

use WC_Order;
use WC_Payment_Token_CC;
use WP_Error;

class Api {

	const API_BASE_URL = 'https://gateway20.pelecard.biz/';

	/**
	 * @param \WC_Order         $order
	 * @param \Pelecard\Gateway $gateway
	 *
	 * @return array|mixed|\WP_Error
	 */
	public static function get_checkout_iframe_url( WC_Order $order, Gateway $gateway ) {
		$args = apply_filters( 'wpg/checkout/iframe_args', [
			'terminal' => $gateway->get_terminal(),
			'user' => $gateway->get_username(),
			'password' => $gateway->get_password(),
			'GoodURL' => $gateway->get_return_url( $order ),
			'ErrorURL' => $gateway->get_return_url( $order ),
			'CancelURL' => 'yes' === $gateway->get_option( 'cancel_button' ) ? $order->get_cancel_order_url() : '',
			'ActionType' => $gateway->get_action_type(),
			'Currency' => self::get_currency( $order ),
			'Total' => $order->get_total() * 100,
			'FreeTotal' => 'yes' === $gateway->get_option( 'free_total' ),
			'ServerSideGoodFeedbackURL' => WC()->api_request_url( Gateway::instance()->id ),
			'ServerSideErrorFeedbackURL' => WC()->api_request_url( Gateway::instance()->id ),
			'CreateToken' => true,
			'Language' => $gateway->get_option( 'language' ),
			'CardHolderName' => $gateway->get_option( 'card_holder_name' ),
			'CustomerIdField' => $gateway->get_option( 'customer_id_field' ),
			'Cvv2Field' => $gateway->get_option( 'cvv2_field' ),
			'EmailField' => 'value' === $gateway->get_option( 'email_field' ) ? $order->get_billing_email() : $gateway->get_option( 'email_field' ),
			'TelField' => 'value' === $gateway->get_option( 'tel_field' ) ? $order->get_billing_phone() : $gateway->get_option( 'tel_field' ),
			'SplitCCNumber' => 'yes' === $gateway->get_option( 'split_cc_number' ),
			'FeedbackOnTop' => true,
			'FeedbackDataTransferMethod' => 'POST',
			'MaxPayments' => $gateway->get_maximum_payments($order),
			'MinPayments' => $gateway->get_minimum_payments($order),
			'MinPaymentsForCredit' => $gateway->get_option( 'min_credit' ),
			/*'FirstPayment' => $gateway->get_option( 'first_payment' ),*/
			'ParamX' => $order->get_id(),
			'UserKey' => $order->get_order_key(),
			'SetFocus' => $gateway->get_option( 'set_focus' ),
			'CssURL' => $gateway->get_option( 'css_url' ),
			'TopText' => $gateway->get_option( 'top_text' ),
			'BottomText' => $gateway->get_option( 'bottom_text' ),
			'LogoURL' => $gateway->get_option( 'logo_url' ),
			'ShowConfirmationCheckbox' => $gateway->get_option( 'confirmation_cb' ),
			'TextOnConfirmationBox' => $gateway->get_option( 'confirmation_text' ),
			'ConfirmationLink' => $gateway->get_option( 'confirmation_url' ),
			'HiddenPelecardLogo' => 'no' === $gateway->get_option( 'hidden_pelecard_logo' ),
			'SupportedCards' => $gateway->get_supported_cards(),
			'UserData' => [
				'UserData1' => $order->get_user_id(),
				'UserData2' => $gateway->get_timeout_url( $order ),
			],
		], $order, $gateway );

		return self::request_iframe_url( $args );
	}

	/**
	 * @param \WC_Order|null $order
	 *
	 * @return mixed|void
	 */
	public static function get_currency( $order = null ) {
		$currency = ( $order ? $order->get_currency() : false ) ?: get_woocommerce_currency();

		$currencies = apply_filters( 'wpg/currencies', [
			'ILS' => 1,
			'USD' => 2,
			'GBP' => 826,
			'EUR' => 978,
		] );

		$currency_code = $currencies[ $currency ] ?? $currency;

		return apply_filters( 'wpg/currency_code', $currency_code, $currency, $currencies, $order );
	}

	/**
	 * @param $args
	 *
	 * @return array|mixed|\WP_Error
	 */
	private static function request_iframe_url( $args ) {
		$response = self::request( 'PaymentGW/init', $args );

		// HTTP errors
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// API errors
		$api_errors = array_filter( $response['Error'] ?? [] );
		if ( empty( $response['URL'] ) && ! empty( $api_errors ) ) {
			return new WP_Error( $api_errors['ErrCode'], $api_errors['ErrMsg'] );
		}

		return $response['URL'];
	}

	/**
	 * @param string $endpoint
	 * @param array  $data
	 *
	 * @return array|mixed|\WP_Error
	 */
	private static function request( string $endpoint, array $data ) {
		$url = self::API_BASE_URL . $endpoint;
		$request_body = apply_filters( 'wpg/api/request_body', $data, $endpoint );

		$args = apply_filters( 'wpg/api/request_args', [
			'body' => json_encode( $request_body ),
			'user-agent' => 'WooCommerce ' . WC()->version,
			'timeout' => 30,
		] );

		Log::info( 'POST REQUEST: ' . $url );
		Log::debug( $request_body );

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			Log::error( 'POST RESPONSE - ERROR' );
			Log::error( $response->get_error_message() );

			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$result = json_decode( $body, true );

		if ( is_null( $result ) ) {
			return new WP_Error(
				'error_occurred',
				__( 'Sorry, an error occurred while processing your request.', 'woo-pelecard-gateway' )
			);
		}

		Log::info( 'POST RESPONSE - SUCCESS' );
		Log::debug( $result );

		return $result;
	}

	/**
	 * @param \Pelecard\Gateway $gateway
	 *
	 * @return array|mixed|\WP_Error
	 */
	public static function get_my_account_iframe_url( Gateway $gateway ) {
		$args = apply_filters( 'wpg/my_account/iframe_args', [
			'terminal' => $gateway->get_terminal(),
			'user' => $gateway->get_username(),
			'password' => $gateway->get_password(),
			'GoodURL' => wc_get_endpoint_url( 'payment-methods' ),
			'ErrorURL' => wc_get_endpoint_url( 'payment-methods' ),
			'CancelURL' => 'yes' === $gateway->get_option( 'cancel_button' ) ? wc_get_endpoint_url( 'payment-methods' ) : '',
			'ActionType' => 'J2',
			'Currency' => self::get_currency(),
			'Total' => 100,
			'FreeTotal' => 'yes' === $gateway->get_option( 'free_total' ),
			'CreateToken' => true,
			'Language' => $gateway->get_option( 'language' ),
			'CardHolderName' => $gateway->get_option( 'card_holder_name' ),
			'CustomerIdField' => $gateway->get_option( 'customer_id_field' ),
			'Cvv2Field' => $gateway->get_option( 'cvv2_field' ),
			'SplitCCNumber' => 'yes' === $gateway->get_option( 'split_cc_number' ),
			'FeedbackOnTop' => true,
			'FeedbackDataTransferMethod' => 'POST',
			'MaxPayments' => 1,
			'MinPayments' => 1,
			'MinPaymentsForCredit' => 2,
			'UserKey' => $gateway->get_user_nonce(),
			'SetFocus' => $gateway->get_option( 'set_focus' ),
			'CssURL' => $gateway->get_option( 'css_url' ),
			'TopText' => $gateway->get_option( 'top_text' ),
			'BottomText' => $gateway->get_option( 'bottom_text' ),
			'LogoURL' => $gateway->get_option( 'logo_url' ),
			'ShowConfirmationCheckbox' => $gateway->get_option( 'confirmation_cb' ),
			'TextOnConfirmationBox' => $gateway->get_option( 'confirmation_text' ),
			'ConfirmationLink' => $gateway->get_option( 'confirmation_url' ),
			'HiddenPelecardLogo' => 'no' === $gateway->get_option( 'hidden_pelecard_logo' ),
			'SupportedCards' => $gateway->get_supported_cards(),
			'UserData' => [
				'UserData1' => get_current_user_id(),
				'UserData2' => wc_get_endpoint_url( 'payment-methods' ),
			],
			'CaptionSet' => [
				'cs_submit' => __( 'Add Card', 'woo-pelecard-gateway' ),
			],
		], $gateway );

		return self::request_iframe_url( $args );
	}

	/**
	 * @param \WC_Order            $order
	 * @param \Pelecard\Gateway    $gateway
	 * @param \WC_Payment_Token_CC $token
	 *
	 * @return array|mixed|\WP_Error
	 */
	public static function charge_by_token( WC_Order $order, Gateway $gateway, WC_Payment_Token_CC $token ) {
		$args = [
			'terminalNumber' => $gateway->get_terminal( true ),
			'user' => $gateway->get_username( true ),
			'password' => $gateway->get_password( true ),
			'token' => $token->get_token(),
			'total' => $order->get_total() * 100,
			'currency' => self::get_currency( $order ),
			'paramX' => $order->get_id(),
			'UserKey' => $order->get_order_key(),
		];

		$service = 'J5' === $gateway->get_action_type()
			? 'AuthorizeCreditCard'
			: 'DebitRegularType';

		$total_payments = $gateway->get_total_payments();
		if ( $total_payments > 1 ) {
			$args['paymentsNumber'] = $total_payments;

			if ( 'J5' === $gateway->get_action_type() ) {
				$service = 'AuthorizePaymentsType';
			} else {
				$service = $total_payments < $gateway->get_minimum_credit_payments()
					? 'DebitPaymentsType'
					: 'DebitCreditType';
			}
		}

		// @todo: if refund, don't send auth number
		$auth_number = $order->get_meta( '_wpg_auth_number' );
		if ( ! empty( $auth_number ) ) {
			$args['authorizationNumber'] = $auth_number;
		}

		// 3DSecure params
		$three_d_secure_params = Order::get_3ds_params( $order );
		if ( ! empty( $three_d_secure_params ) ) {
			$args['Eci'] = $three_d_secure_params[0];
			$args['XID'] = $three_d_secure_params[1];
			$args['Cavv'] = $three_d_secure_params[2];
		}

		Log::info( sprintf( 'CHARGE WITH TOKEN (%s)', $gateway->get_action_type() ) );
		if ( 0 < $order->get_id() ) {
			Log::info( sprintf( 'ORDER #%d: PAYMENT START', $order->get_id() ) );
		}

		$args = apply_filters( 'wpg/checkout/rest_args', $args, $order, $gateway, $token, $service );

		$response = self::request( "services/{$service}", $args );

		if ( is_wp_error( $response ) ) {
			Log::error( $response->get_error_message() );

			return $response;
		}

		return $response;
	}

	/**
	 * @param string $confirmation_key
	 * @param string $debit_total
	 * @param string $unique_key
	 *
	 * @return bool
	 */
	public static function validate_by_unique_key( string $confirmation_key, string $debit_total, string $unique_key ): bool {
		$args = [
			'ConfirmationKey' => $confirmation_key,
			'TotalX100' => $debit_total,
			'UniqueKey' => $unique_key,
		];

		$response = self::request( 'PaymentGW/ValidateByUniqueKey', $args );
		if ( is_wp_error( $response ) ) {
			return false;
		}

		return (bool) $response;
	}

	/**
	 * @param string $transaction_id
	 *
	 * @return array|mixed|\WP_Error
	 */
	public static function get_transaction_by_id( string $transaction_id ) {
		$gateway = Gateway::instance();

		$args = [
			'terminal' => $gateway->get_terminal(),
			'user' => $gateway->get_username(),
			'password' => $gateway->get_password(),
			'TransactionId' => $transaction_id,
		];

		return self::request( 'PaymentGW/GetTransaction', $args );
	}
}
