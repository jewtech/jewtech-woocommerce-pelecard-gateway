<?php

namespace Pelecard;

use DirectoryIterator;
use Pelecard\Traits\Singleton;
use ReflectionClass;
use ReflectionException;

/**
 * Class Plugin
 */
class Plugin {

	use Singleton;

	/**
	 * @var string $version
	 */
	public static $version = '1.4.8';

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', [ $this, 'register_hooks' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front_scripts' ], 20 );
		add_filter( 'woocommerce_payment_gateways', [ $this, 'add_payment_gateway' ], 10, 1 );

		Order::instance();
		Legacy::instance();

		/**
		 * Load Invoices
		 */
		$this->load( 'Invoices' );
	}

	/**
	 * @param string $directory
	 */
	public function load( string $directory ) {
		$iterator = new DirectoryIterator( __DIR__ . DIRECTORY_SEPARATOR . $directory );

		foreach ( $iterator as $file ) {
			// Verify this is not a folder
			if ( ! $file->isFile() ) {
				continue;
			}

			// Skip if no php file
			if ( 'php' !== ( $extension = $file->getExtension() ) ) {
				continue;
			}

			// Get name without extension
			$basename = $file->getBasename( '.' . $extension );
			$class = __NAMESPACE__ . '\\' . $directory . '\\' . $basename;

			// Skip if class cannot be initiated
			if ( ! $this->class_has_method( $class, 'instance' ) ) {
				continue;
			}

			$class::instance();
		}
	}

	/**
	 * @param string $class
	 * @param string $method
	 *
	 * @return bool
	 */
	public function class_has_method( string $class, string $method ): bool {
		try {
			return ( new ReflectionClass( $class ) )->hasMethod( $method );
		} catch ( ReflectionException $e ) {
			return false;
		}
	}

	/**
	 * @return string
	 */
	public static function get_templates_path(): string {
		return self::get_directory_path() . '/templates/';
	}

	/**
	 * @return string
	 */
	public static function get_directory_path(): string {
		return untrailingslashit( plugin_dir_path( WPG_FILE ) );
	}

	/**
	 * @return string
	 */
	public static function get_directory_url(): string {
		return untrailingslashit( plugin_dir_url( WPG_FILE ) );
	}

	/**
	 * @param string $migration
	 *
	 * @return string
	 */
	public static function get_migrations_path( string $migration ): string {
		return self::get_directory_path() . '/migrations/' . $migration;
	}

	public function register_hooks() {
		add_action( 'wp', [ Gateway::instance(), 'maybe_process_redirect_order' ] );
	}

	/**
	 * @param array $gateways
	 *
	 * @return array
	 */
	public function add_payment_gateway( array $gateways ): array {
		$gateways[] = Gateway::instance();

		return $gateways;
	}

	public function enqueue_front_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'wpg-checkout',
			self::get_directory_url() . '/assets/js/checkout' . $suffix . '.js',
			[ 'jquery' ],
			self::$version
		);
	}
}
