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
		return str_replace( [ 'ä', 'ü', 'ö', 'ß', ' ' ], [
			'ae',
			'ue',
			'oe',
			'ss',
			'%20'
		], mb_strtolower( (string) $string ) );
	}
}
