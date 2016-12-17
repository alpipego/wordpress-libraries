<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 08.12.2016
 * Time: 14:43
 */
declare( strict_types = 1 );
namespace Alpipego\WpLib\Custom;


abstract class AbstractCustom {
	protected $singular;
	protected $plural;
	protected $labels = [];
	protected $roles = [ 'administrator' ];
	protected $args = [ 'public' => true ];
	protected $capability_type = 'post';
	protected $capabilities = [];
	/** @var  Capabilities $capObj */
	protected $capObj;

	public function __call( $name, $arguments ) {
		$this->__set( $name, $arguments[0] );

		return $this;
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
		} else {
			$this->args[ $name ] = $value;
		}

		return $this;
	}

	public function capabilities( array $caps = [] ) {
		if ( ! empty( $caps ) ) {
			$this->capabilities = [ 'capabilities' => $caps ];
		} else {
			$this->capabilities = $this->defaultCaps();
		}

		return $this;
	}

	abstract protected function defaultCaps() : array;

	protected function labels( array $labels = [] ) {
		if ( ! empty( $labels ) ) {
			$this->labels = $labels;
		} elseif ( empty( $this->labels ) ) {
			$this->labels = $this->defaultLabels();
		}

		return $this;
	}

	abstract protected function defaultLabels() : array;

}
