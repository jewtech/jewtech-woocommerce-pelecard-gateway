<?php

namespace Pelecard;

use Pelecard\Traits\Singleton;

/**
 * Class Session
 */
class Session extends \WC_Session_Handler {

	use Singleton;

	/**
	 * @param int $customer_id
	 *
	 * @return $this
	 */
	public function set_customer_id( int $customer_id ) {
		$this->_customer_id = $customer_id;

		return $this;
	}

	/**
	 * Set a session variable.
	 *
	 * @param string $key Key to set.
	 * @param mixed  $value Value to set.
	 *
	 * @return $this
	 */
	public function set( $key, $value ) {
		parent::set( $key, $value );

		return $this;
	}

	/**
	 * @return $this
	 */
	public function init_current_session_data() {
		$this->_data = $this->get_session_data();

		return $this;
	}

	/**
	 * @return bool
	 */
	public function has_session() {
		return parent::has_session() || 0 < $this->_customer_id;
	}

	/**
	 * @param $notices
	 *
	 * @return $this
	 */
	public function set_notices( $notices ) {
		$this->set( 'wc_notices', $notices );

		return $this;
	}
}
