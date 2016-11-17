<?php

namespace Alpipego\WpLib;

class Sanitizer {
	public function name( $string ) {
		return str_replace( [ 'ä', 'ü', 'ö', 'ß' ], [
			'ae',
			'ue',
			'oe',
			'ss'
		], preg_replace( '%\h%', '-', mb_strtolower( (string) $string ) ) );
	}

	public function permalink( $string ) {
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
