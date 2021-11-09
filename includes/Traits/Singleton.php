<?php

namespace Pelecard\Traits;

/**
 * Trait Singleton
 */
trait Singleton {

	/**
	 * @return mixed|static
	 */
	final public static function instance() {
		static $instance;

		return $instance[ static::class ] ?? ( $instance[ static::class ] = new static );
	}
}
