<?php

namespace Alpipego\WpLib;

/**
 * Class Autoload
 * @package Alpipego\WpLib
 */
class Autoload {
	/**
	 * @var string
	 */
	private $dir;
	/**
	 * @var string
	 */
	private $namespace;

	/**
	 * Autoload constructor.
	 *
	 * @param string $dir
	 * @param string $ns
	 */
	public function __construct( string $dir, string $ns ) {
		$this->dir = $dir;
		$this->namespace  = $ns;
		$this->load();
	}

	/**
	 * Load requested classes
	 */
	public function load() {
		\spl_autoload_register( function ( $class ) {
			$class = ltrim( $class, '\\' );

			if ( strpos( $class, $this->namespace ) !== 0 ) {
				return;
			}

			$class = str_replace( $this->namespace, '', $class );
			$path  = $this->dir . str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';

			require_once $path;
		} );
	}
}
