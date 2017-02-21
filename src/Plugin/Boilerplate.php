<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 17/10/2016
 * Time: 18:39
 */
declare( strict_types = 1 );
namespace Alpipego\WpLib\Plugin;


class Boilerplate implements PluginInterface {
	public $file = '';
	public $path = '';
	public $version = '';
	public $textDomain = '';
	public $languagesDir = '';

	public function __construct( string $file ) {
		$this->file = $file;
		$this->path = $this->getPath();
		// check if acf plugin is active and pass custom (static) activation function on activation
//		$this->activationHook( $activation );
//		$this->deactivationHook( $deactivation );
//		$this->textDomain   = $textdomain;
//		$this->languagesDir = $languagesDir;
	}

	public function getPath() {
		$path = explode( '/', $this->path );

		return \trailingslashit( WP_PLUGIN_DIR ) . $path[0];
	}

	public function run() {
		add_action( 'plugins_loaded', [ $this, 'loadTextdomain' ] );
	}

	public function __call( $name, $arguments ) {
		$this->$name = $arguments[0];

		return $this;
	}

	/**
	 * wrapper for `load_plugin_textdomain`
	 *
	 * @param string $dir path to languages dir
	 */
	public function loadTextdomain() {
		if ( ! empty( $this->textDomain ) ) {
			\load_plugin_textdomain( $this->textDomain, false, $this->languagesDir );
		}
	}

	public function uninstall( callable $func ) {
		\register_uninstall_hook( $this->file, function () use ( $func ) {
			call_user_func( $func );
		} );
	}

	public function activation( callable $func ) {
		\register_activation_hook( $this->file, function () use ( $func ) {
			call_user_func( $func );
		} );
	}

	public function deactivation( callable $func ) {
		\register_deactivation_hook( $this->file, function () use ( $func ) {
			call_user_func( $func );
		} );
	}
}
