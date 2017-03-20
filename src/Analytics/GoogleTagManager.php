<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 06.02.2017
 * Time: 11:48
 */
declare( strict_types = 1 );
namespace Alpipego\WpLib\Analytics;

class GoogleTagManager {
	private $id;

	public function __construct( string $id ) {
		$this->id = $id;
	}

	public function run() {
		if ( ! empty( $this->id ) ) {
			add_filter( 'alpipego/libs/analytics/gtm/noscript', function () {
				return $this->gtmNoScript( $this->id );
			} );

			add_filter( 'alpipego/libs/analytics/gtm/script', function () {
				return $this->gtmScript( $this->id );
			} );
		}
	}

	public function gtmNoScript( $id ) {
		$args = apply_filters( 'alpipego/libs/analytics/gtm/noscript/args', [] );
		$url  = add_query_arg( $args, 'https://www.googletagmanager.com/ns.html?id=' . $this->id );

		return "<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src=\"{$url}\"
			height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->";
	}

	public function gtmScript( $id ) {
		return "<!-- Google Tag Manager -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','{$this->id}');</script>
			<!-- End Google Tag Manager -->";
	}
}
