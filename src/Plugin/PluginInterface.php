<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 08.12.2016
 * Time: 11:06
 */
declare( strict_types = 1 );
namespace Alpipego\WpLib\Plugin;


interface PluginInterface {
	public function activation( callable $func );

	public function deactivation( callable $func );

	public function uninstall( callable $func );
}
