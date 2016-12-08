<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 05.12.2016
 * Time: 13:49
 */

namespace Alpipego\WpLib\Assets;

abstract class AbstractAssets {
	protected $assets = [];
	/**
	 * @var \WP_Styles | \WP_Scripts $collection
	 */
	protected $collection;

	/**
	 * @var string $group either 'style' or 'script'
	 */
	protected $group;

	public function run() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register' ], 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueAssets' ] );
	}

	abstract public function register();

	public function enqueueAssets() {
		/** @var Asset $asset */
		foreach ( $this->assets as $asset ) {
			$asset = $this->action( $asset );
			$this->{$asset->action}( $asset );
		}
	}

	protected function action( Asset $asset ) {
		$queued = in_array( $asset->handle, $this->collection->queue, true );

		if ( ! isset( $asset->action ) ) {
			return $asset;
		}

		$merged = array_merge( (array) $asset, (array) $this->collection->registered[ $asset->handle ] );

		if ( ! $queued && $asset->action !== 'inline' ) {
			// asset not yet queued
			$asset->action = 'enqueue';
		} elseif ( $asset->action === 'add' ) {
			// asset is already queued (possibly changed condition)
			foreach ( $merged as $field => $value ) {
				$asset->$field = $value;
			}
			$asset->action = 'requeue';
		} elseif ( $asset->action === 'remove' ) {
			// asset is queued, should be removed (still looks at condition)
			$asset->action = 'dequeue';
		} elseif ( $asset->action === 'inline' || $asset->action === 'update' ) {
			foreach ( $merged as $field => $value ) {
				$asset->$field = $value;
			}
		}

		return $asset;
	}

	public function lazyAssets( $tag, $handle ) {
		/** @var Asset $asset */
		foreach ( $this->assets as $asset ) {
			if ( $asset->handle === $handle && isset( $asset->prio ) ) {
				if ( $asset->prio === 'defer' ) {
					return str_replace( ' src', ' defer="defer" src', $tag );
				} elseif ( $asset->prio === 'async' ) {
					return str_replace( ' src', ' async="async" src', $tag );
				}
			}
		}

		return $tag;
	}

	protected function getSrc( Asset $asset, string $fragment ) : string {
		return sprintf( '%1$s/%2$s/%3$s.%2$s', get_template_directory_uri(), $fragment, $asset->handle );
	}

	private function requeue( Asset $asset ) {
		$func = "wp_dequeue_{$this->group}";
		$func( $asset->handle );
		$this->enqueue( $asset );
	}

	private function enqueue( Asset $asset ) {
		if ( $asset->condition ) {
			$func = "wp_enqueue_{$this->group}";
			$func( $asset->handle );
		}
	}

	private function update( Asset $asset ) {
		if ( $asset->condition ) {
			foreach ( $asset->data as $key => $value ) {
				$this->collection->add_data( $asset->handle, $key, $value );
			}
		}
	}

	private function inline( Asset $asset ) {
		if ( $asset->condition ) {
			$path = preg_replace( '%(?:https?:)?//[^/]+(/.+)$%', '$1', $asset->src );
			$file = array_filter( [ ABSPATH . $path, ABSPATH . '..' . $path ], 'file_exists' );
			if ( ! empty( $file ) ) {
				$file = array_shift( $file );
				if ( filesize( $file ) < 2000 ) {
					$contents = file_get_contents( $file );
					$contents = preg_replace( '%[\t\n]|\h{2,}%', '', $contents );
					$contents = preg_replace( '%(/\*.+?\*/)%', '', $contents );
					$this->dequeue( $asset );

					$dependency = end( $asset->deps );
					if ( $dependency ) {
						$func = "wp_add_inline_{$this->group}";
						$func( end( $asset->deps ), $contents );
					} else {
						$action = isset( $this->footer ) && $this->footer ? 'wp_footer' : 'wp_head';
						add_action( $action, function () use ( $contents ) {
							printf( '<%1$s>%2$s</%1$s>', $this->group, $contents );
						} );
					}
				}
			}
		}
	}

	private function dequeue( Asset $asset ) {
		if ( $asset->condition ) {
			$func = "wp_dequeue_{$this->group}";
			$func( $asset->handle );
		}
	}
}
