<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 09/08/16
 * Time: 12:35
 */

namespace Alpipego\WpLib;

use Pimple\Container;
use ReflectionClass;
/**
 * Extends Pimple Container
 * @package Alpipego\WpLib
 */
class DIContainer extends Container {
	/**
	 * Calls `run()` method on all objects registered on plugin container
	 */
	public function run() {
		foreach ( $this->keys() as $key ) { // Loop on contents
			$content = $this->offsetGet( $key );
			if ( is_object( $content ) ) {
				$reflection = new ReflectionClass( $content );
				if ( $reflection->hasMethod( 'run' ) ) {
					$content->run(); // Call run method on object
				}
			}
		}
	}
}
