<?php
namespace Alpipego\WpLib\Assets;

/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 05.12.2016
 * Time: 13:17
 */
use _WP_Dependency;

class Asset {
	public $handle;
	public $condition = true;
	public $src = '';
	public $ver = null;
	public $deps = [];
	public $extra = [];
	public $action = '';
	public $prio = '';
	public $localize = [];
	public $min = false;
	public $data = [];
	public $footer = false;

	public function __construct( $handle ) {
		$this->handle = $handle;
	}

	public function __call( $name, $args ) {
		return $this->__set( $name, $args[0] );
	}

	public function __set( $name, $value ) {
		if ( property_exists( $this, $name ) ) {
			if ( is_array( $this->$name ) ) {
				if ( ! is_array( $value ) ) {
					$this->$name[] = $value;
				} else {
					$this->$name = array_merge( $this->$name, $value );
				}
			} else {
				$this->$name = $value;
			}
		}

		return $this;
	}

	public function condition( callable $cond ) {
		// TODO is this the best way to defer function here?
		add_action( 'wp', function () use ( $cond ) {
			$this->condition = call_user_func( $cond );
		} );

		return $this;
	}
}
