<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 05.12.2016
 * Time: 17:33
 */

namespace Alpipego\WpLib\Assets;


class Scripts extends AbstractAssets {
	public function __construct( array $scripts ) {
		$this->assets     = $scripts;
		$this->collection = wp_scripts();
		$this->group      = 'script';
	}

	public function run() {
		parent::run();
		add_filter( 'script_loader_tag', [ $this, 'deferScripts' ], 10, 2 );
	}

	public function register() {
		$version = $this->ver ?? WP_ENV !== 'production' ? time() : false;
		/** @var Script $script */
		foreach ( $this->assets as $script ) {
			if ( in_array( $script->handle, array_keys( $this->collection->registered ) ) ) {
				continue;
			}
			wp_register_script( $script->handle, $this->path ?? $this->getSrc( $script, 'js' ), $this->deps ?? [], $version, $this->footer ?? true );
		}
	}

	public function deferScripts( $tag, $handle ) {
		/** @var Script $asset */
		foreach ( $this->assets as $asset ) {
			if ( $asset->handle === $handle ) {
				if ( $asset->prio === 'defer' ) {
					return str_replace( ' src', ' defer="defer" src', $tag );
				}
				if ( $asset->prio === 'async' ) {
					return str_replace( ' src', ' async="async" src', $tag );
				}
			}
		}

		return $tag;
	}
}
