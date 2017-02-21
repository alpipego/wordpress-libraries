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
	 * @param $pluginPath
	 */
	function __construct( $pluginPath, $page ) {
		$this->viewsPath = $pluginPath . 'views/';
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
	function callback() {
		$args = [
			'page' => $this->page,
		];

		include $this->viewsPath . 'page/' . $this->page . '.php';
	}
}
