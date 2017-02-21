<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 06.12.2016
 * Time: 09:41
 */
declare( strict_types = 1 );
namespace Alpipego\WpLib\Assets;


class AssetsCollection {
	private $assets = [];

	public function run() {
		foreach ( $this->assets as $group => $assets ) {
			$classname = __NAMESPACE__ . '\\' . $group . 's';
			if ( class_exists( $classname ) ) {
				( new $classname( $assets ) )->run();
			}
		}
	}

	public function __call( $name, $args ) {
		if ( $args[0] instanceof Asset ) {
			$asset         = $args[0];
			$asset->action = $name;
			$type          = $this->getType( $asset );

			return $this->assets[ $type ][] = $asset;
		}

		return $this;
	}

	private function getType( Asset $asset ) {
		$class = explode( '\\', get_class( $asset ) );

		return end( $class );
	}
}
