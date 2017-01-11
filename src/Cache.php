<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 14.12.2016
 * Time: 16:16
 */

namespace Alpipego\WpLib;


class Cache {
	public $cloudflare = false;
	public $cache = true;
	public $loggedin = false;
	public $debug = false;
	public $msg = '';
	public $domain;
	public $url;

	public function __construct() {
		$this->domain = $_SERVER['HTTP_HOST'];
		$this->path   = $_SERVER['REQUEST_URI'];
		$this->setUrl()->decide();
	}

	private function decide() {
		// don't cache if cloudflare is enabled and request from cloudflare
		if ( $this->cloudflare && isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
			$this->cache = false;
			$this->addMessage( 'request from cloudflare ' );
		}
		// don't cache if user is logged in
		if ( strpos( 'test ' . implode( ' ', array_keys( $_COOKIE ) ), 'wordpress_logged_in' ) ) {
			$this->cache    = false;
			$this->loggedin = true;
			$this->addMessage( 'loggedin user ' );
		}
		// don't cache post requests
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			$cache = false;
			$this->addMessage( 'post request ' );
		}
		// don't cache requests to wordpress php files
		if ( preg_match( '%(/wp-admin|/xmlrpc.php|/wp-(app|cron|login|register|mail).php|wp-.*.php|/feed/|index.php|wp-comments-popup.php|wp-links-opml.php|wp-locations.php|sitemap(_index)?.xml|[a-z0-9_-]+-sitemap([0-9]+)?.xml)%', $this->path ) ) {
			$this->cache = false;
			$this->addMessage( 'wordpress file ' );
		}

		// don't cache if url has a query string
		// TODO verify that this is reasonable
		if ( parse_url( $this->url, PHP_URL_QUERY ) ) {
			$this->cache = false;
			$this->addMessage( 'query string ' );
		}

	}

	public function addMessage( string $msg ) {
		$this->msg .= $msg;

		return $this;
	}

	private function setUrl() {
		$url       = $this->path;
		$url       = preg_replace( '%^(.+?)[?&]purge=(?:(?:document)|(?:site))(.*?)$%', '$1$2', $url );
		$this->url = $url . ( substr( $url, - 1 ) === '/' ? '' : '/' );

		return $this;
	}

	// time diff
	public function time( $start, $end ) {
		$t = ( $this->getmicrotime( $end ) - $this->getmicrotime( $start ) );

		return round( $t, 5 );
	}

	// get time
	private function getmicrotime( $t ) {
		list( $usec, $sec ) = explode( ' ', $t );

		return ( (float) $usec + (float) $sec );
	}
}
