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
	public $ver = false;
	public $deps = [];
	public $extra = [];
	public $action = '';
	public $data = [];

	public function __construct( $handle ) {
		$this->handle = $handle;
	}

	public function __call( $name, $args ) {
		return $this->__set( $name, $args[0] );
	}

	public function __set( $name, $args ) {
		$this->{$name} = $args;

		return $this;
	}

	public function condition( callable $cond ) {
		$this->condition = call_user_func( $cond );

		return $this;
	}
}
