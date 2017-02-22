<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 17/10/2016
 * Time: 18:42
 */

namespace Alpipego\WpLib\Plugin;


class AcfBoilerplate extends Boilerplate {
	public function run() {
		// check if acf plugin is active
		$this->checkDependencies();

		// setup acf to import this plugins fields
		$this->includeFields();
	}

	protected function checkDependencies() {
		add_action( 'admin_init', function () {
			if ( ! class_exists( '\acf' ) ) {
				deactivate_plugins( $this->path );
				add_action( 'admin_notices', function () {
					printf( '<div class="error"><p>%s</p></div>', esc_html__( 'Please activate <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> first.', 'alpipego-wplib' ) );
				} );
			}
		} );
	}

	private function includeFields() {
		$this->path = \plugin_basename( $this->file );
		add_filter( 'acf/settings/load_json', function ( $paths ) {
			$paths[] = $this->getPath() . '/inc';

			return $paths;
		}, 9 );
	}
}
