<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 21/07/16
 * Time: 17:36
 */

namespace Alpipego\WpLib\Options;

/**
 * Interface OptionsPage
 * @package Alpipego\WpLib
 */
interface OptionsPageInterface {
	/**
	 * Add the page to admin menu action
	 */
	public function run();

	/**
	 * Add the (sub)page
	 */
	public function addPage();

	/**
	 * Include the view
	 */
	public function callback();
}
