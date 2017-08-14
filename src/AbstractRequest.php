<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 17.01.2017
 * Time: 13:40
 */

namespace Alpipego\WpLib;


/**
 * Class AbstractRequest
 * @package Alpipego\WpLib
 */
abstract class AbstractRequest {
	/**
	 * AbstractRequest constructor.
	 */
	public function __construct() {
		if ( ! isset( $_SESSION ) ) {
			session_start();
		}
	}

	/**
	 * @param $action
	 *
	 * @return bool
	 */
	protected function verify( string $action ) : bool {
		$ajax = ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest';

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], $action ) ) {
			if ( $ajax ) {
				wp_send_json_error(null, 403);
			} else {
				wp_redirect( wp_get_referer() );
				exit();
			}
		}

		return $ajax;
	}

	/**
	 * @param array $data
	 */
	protected function redirect( array $data = [] ) {
		foreach ( $_SESSION as $sessionVar => $value ) {
			unset( $_SESSION[ $sessionVar ] );
		}
		$_SESSION = $data;
		wp_redirect( wp_get_referer() );
		exit();
	}
}
