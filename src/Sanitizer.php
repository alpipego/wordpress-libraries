<?php
declare( strict_types = 1 );
namespace Alpipego\WpLib;

/**
 * Class Sanitizer sanitize German Strings
 * @package Alpipego\WpLib
 */
class Sanitizer {
	/**
	 * @deprecated 4.0.0
	 * @param string $string
	 *
	 * @return string
	 */
	public function name( string $string ) : string {
		return str_replace( [ 'ä', 'ü', 'ö', 'ß' ], [
			'ae',
			'ue',
			'oe',
			'ss'
		], preg_replace( '%\h%', '-', mb_strtolower( (string) $string ) ) );
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public function permalink( string $string ) : string {
		// remove any whitespace
		$string = preg_replace( '%\h%', '-', mb_strtolower( (string) $string ) );
		// convert German Umlauts and SZ
		$string = str_replace( [ 'ä', 'ü', 'ö', 'ß' ], [ 'ae', 'ue', 'oe', 'ss' ], $string );
		// replace anything that is not a-z or digit
		$string = preg_replace( '%[^a-z0-9\-]%', '', $string );
		// replace multiple dashes with one
		$string = preg_replace( '%-+%', '-', $string );

		return $string;
	}
}
