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
		$this->assets = $scripts;
		$this->collection = wp_scripts();
		$this->group = 'script';
	}

	public function run() {
		parent::run();
		add_filter( 'script_loader_tag', [ $this, 'deferScripts' ], 10, 2 );
	}

	public function register() {
		/** @var Script $script */
		foreach ( $this->assets as $script ) {
			wp_register_script( $script->handle, $this->path ?? $this->getSrc( $script, 'js' ), $this->deps ?? [], $this->ver ?? false, $this->footer ?? true );
		}
	}
}
