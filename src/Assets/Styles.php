<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 05.12.2016
 * Time: 17:29
 */

namespace Alpipego\WpLib\Assets;


class Styles extends AbstractAssets {
	public function __construct( array $assets ) {
		$this->assets   = $assets;
		$this->collection = wp_styles();
		$this->group = 'style';
	}

	public function run() {
		parent::run();
		add_filter( 'style_loader_tag', [ $this, 'lazyAssets' ], 10, 2 );
	}

	public function register() {
		foreach ( $this->assets as $style ) {
			if ( ! array_key_exists( $style->handle, $this->collection->registered ) ) {
				wp_register_style( $style->handle, $style->src ?: $this->getSrc( $style, 'css' ), $style->deps ?? [], $style->ver ?? false, $this->media ?? 'screen' );
			}
		}
	}
}
