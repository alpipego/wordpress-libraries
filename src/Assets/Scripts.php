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
		/** @var Script $script */
		foreach ( $this->assets as $script ) {
			if ( ! array_key_exists( $script->handle, $this->collection->registered ) ) {
				$script->src = $script->src ?: $this->getSrc( $script, 'js' );
				$script->ver = $script->ver ?? filemtime( $this->getPath( $script, 'js' ) );
				wp_register_script( $script->handle, $script->src, $script->deps ?? [], $script->ver, $script->footer ?? true );
			}
			if ( ! empty( $script->localize ) ) {
				$this->localize( $script );
			}
		}
	}

	private function localize( Script $script ) {
		foreach ( $script->localize as $key => $value ) {
			wp_localize_script( $script->handle, $key, $value );
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
