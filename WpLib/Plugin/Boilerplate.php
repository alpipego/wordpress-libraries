<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 17/10/2016
 * Time: 18:39
 */

namespace Alpipego\WpLib\Plugin;


class Boilerplate {
	protected $file;
	protected $path;
	private $textDomain;
	private $languagesDir;

	public function __construct( $file, $func = null, $textdomain = '', $languagesDir = __DIR__ . 'languages' ) {
		// parent variable
		$this->file         = $file;
		$this->textDomain   = $textdomain;
		$this->languagesDir = $languagesDir;

		// check if acf plugin is active and pass custom (static) activation function on activation
		$this->activationHook( $func );
	}

	protected function activationHook( $func ) {
		\register_activation_hook( $this->file, function () use ( $func ) {
			$this->checkDependencies();
			if ( ! is_null( $func ) ) {
				call_user_func( $func );
			}
		} );
	}

	protected function checkDependencies() {
	}

	public function run() {
		$this->checkDependencies();
		add_action( 'plugins_loaded', [ $this, 'loadTextdomain' ] );
	}

	public function getPath() {
		$path = explode( '/', $this->path );

		return \trailingslashit( WP_PLUGIN_DIR ) . $path[0];
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
}
