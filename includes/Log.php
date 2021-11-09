<?php

namespace Pelecard;

use Throwable;

/**
 * Class Log
 *
 * @method static void emergency( mixed $val, array $context = [] )
 * @method static void alert( mixed $val, array $context = [] )
 * @method static void critical( mixed $val, array $context = [] )
 * @method static void error( mixed $val, array $context = [] )
 * @method static void warning( mixed $val, array $context = [] )
 * @method static void notice( mixed $val, array $context = [] )
 * @method static void info( mixed $val, array $context = [] )
 * @method static void debug( mixed $val, array $context = [] )
 */
class Log {

	const WC_LOG_FILENAME = 'wpg';

	/**
	 * @var null|\WC_Logger $logger
	 */
	public static $logger;

	/**
	 * Handle dynamic, static calls to the object.
	 *
	 * @param $method
	 * @param $args
	 *
	 * @return false
	 */
	public static function __callStatic( $method, $args ) {
		if ( ! class_exists( '\WC_Logger' ) ) {
			return false;
		}

		/**
		 * Utilize WC logger class
		 */
		if ( empty( self::$logger ) ) {
			self::$logger = wc_get_logger();
		}

		list( $val, $context ) = array_merge( $args, [ null, null ] );

		/**
		 * Normalize message
		 */
		if ( is_object( $val ) ) {
			$val = json_encode( $val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
		} elseif ( is_array( $val ) ) {
			$val = wc_print_r( $val, true );
		}

		/**
		 * Bail if message is empty
		 */
		if ( empty( $val ) ) {
			return false;
		}

		/**
		 * Append context
		 */
		$context = [ 'source' => $context ?: self::WC_LOG_FILENAME ];

		try {
			return self::$logger->$method( $val, $context );
		} catch ( Throwable $th ) {
			self::$logger->critical( $th->getMessage(), $context );
		}
	}
}
