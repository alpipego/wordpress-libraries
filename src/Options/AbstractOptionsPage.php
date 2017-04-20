<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 21/07/16
 * Time: 17:36
 */

namespace Alpipego\WpLib\Options;

/**
 * Abstract Class OptionsPage
 * @package Alpipego\WpLib
 */
abstract class AbstractOptionsPage {
	/**
	 * @var string
	 */
	public $page;
	/**
	 * @var string
	 */
	protected $viewsPath;

	/**
	 * OptionsPage constructor.
	 *
	 * @param string $pluginPath
	 * @param string $page
	 */
	function __construct( string $pluginPath, string $page ) {
		$this->viewsPath = trailingslashit( $pluginPath ) . 'views/';
		$this->page      = $page;
	}

	/**
	 * Add the page to admin menu action
	 */
	public function run() {
		\add_action( 'admin_menu', [ $this, 'addPage' ] );
	}

	/**
	 * Include the view
	 */
	public function callback() {
		$args['page'] = $this->page;
		$this->includeView( $args );
	}

	protected function includeView( array $args ) {
		require_once $this->viewsPath . 'page/' . $this->page . '.php';
	}
}
