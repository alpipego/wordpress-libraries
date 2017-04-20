<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 26/07/16
 * Time: 12:10
 */

namespace Alpipego\WpLib\Options;


/**
 * Interface OptionsSectionInterface
 * @package Alpipego\WpLib
 */
interface OptionsSectionInterface {

	/**
	 * @return void
	 */
	public function run();

	/**
	 * Wrapper for `add_settings_section`
	 *
	 * @return void
	 */
	public function addSection();

	/**
	 * @return mixed
	 */
	public function callback();

}
