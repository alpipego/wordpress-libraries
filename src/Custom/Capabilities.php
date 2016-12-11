<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 08.12.2016
 * Time: 13:39
 */
declare( strict_types = 1 );
namespace Alpipego\WpLib\Custom;


class Capabilities {
	protected $capType;
	protected $roles;
	protected $caps;

	public function map( string $capType, array $caps, array $roles ) {
		$this->capType = $capType;
		$this->caps    = $caps['capabilities'] ?? [];
		$this->roles   = array_unique( $roles );

		return $this;
	}

	public function run() {
		if ( ! empty( $this->capType ) ) {
			add_action( 'init', [ $this, 'addToRole' ], 11 );
//			add_action( 'init', [ $this, 'mapMetaCaps' ], 12 );
		}
	}

	public function mapMetaCaps() {
		add_filter( 'map_meta_cap', function ( $caps, $cap, $user_id, $args ) {
			if ( empty( $args ) || empty($caps[0]) ) {
				return $caps;
			}

			$capArray   = explode( '_', $cap );
			$objectType = $this->getObjectType( $capArray );
			if ( ! $objectType ) {
				return $caps;
			}

			echo '<code><pre>';
			    var_dump([$caps, $cap, $objectType, $args[0]]);
			echo '</pre></code>';

			$object = $this->getObject( $objectType, (int) $args[0] );
			$mapper = 'map' . ucfirst( $objectType ) . 'Rules';
			$caps   = $this->$mapper( $object, (int) $user_id, $capArray );

			return $caps;
		}, 10, 4 );
	}


	protected function getObjectType( array $cap ) {
		switch ( end( $cap ) ) {
			case 'categories' :
			case 'term' :
				return 'term';
			case 'post' :
				return 'post';
			default :
				return false;
		}
	}

	protected function getObject( string $objectType, int $objectId ) {
		$func = 'get_' . $objectType;

		return $func( $objectId );
	}

	public function addToRole() {
		foreach ( $this->roles as $role ) {
			$role = \get_role( $role );
			foreach ( $this->caps as $postCap => $cap ) {
				if ( ! empty( $cap ) && ! $role->has_cap( $cap ) ) {
					$role->add_cap( $cap );
				}
			}
		}
	}

	private function mapTermRules( \WP_Term $term, int $userId, array $capArray ) {
		$caps = [];
		$tax  = get_taxonomy( $term->taxonomy );
		echo '<code><pre>';
		var_dump( $tax );
		echo '</pre></code>';
		if ( ! empty( $tax ) ) {

			$cap    = $capArray[0] . '_' . $this->capType;
			$caps[] = $tax->cap->$cap;
		}

		return $caps;
	}

	private function mapPostRules( \WP_Post $post, int $userId, array $capArray ) : array {
		$caps     = [];
		$postType = get_post_type_object( $post->post_type );

		if ( $postType instanceof \WP_Post_Type ) {
			if ( $capArray[0] === 'read' ) {
				if ( $post->post_status === 'private' ) {
					$cap = $capArray[0] . '_private_posts';
				} else {
					$cap = 'read';
				}
			} else {
				if ( (int) $post->post_author === $userId ) {
					$cap = $capArray[0] . '_' . $this->capType;
				} elseif ( $post->post_status === 'private' ) {
					$cap = $capArray[0] . '_private_' . $this->capType . 's';
				} else {
					$cap = $capArray[0] . '_others_' . $this->capType . 's';
				}
			}

//			echo '<code><pre>';
//			    var_dump([$post->post_type, $cap, $postType->cap->$cap, $capArray]);
//			echo '</pre></code>';
			$caps[] = $postType->cap->$cap;
		}

		return $caps;
	}
//	protected function getObject( string $cap, int $objectId ) {
//		$capType     = explode( '_', $cap );
//		$metaCapType = end( $capType );
//		if ( $metaCapType === 'categories' ) {
//			$metaCapType = 'term';
//		}
//
//		$object = get_{
//			$metaCapType}()
//
//		return;
//	}
}
