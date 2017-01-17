<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 17.01.2017
 * Time: 13:40
 */

namespace Alpipego\WpLib;


abstract class AbstractRequest {
	protected function verify( $action ) : bool {
		$ajax = ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest';

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], $action ) ) {
			if ( $ajax ) {
				wp_send_json_error();
			} else {
				wp_redirect( wp_get_referer() );
				exit();
			}
		}

		return $ajax;
	}
}
