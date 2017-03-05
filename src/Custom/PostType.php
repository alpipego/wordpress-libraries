<?php

namespace Alpipego\WpLib\Custom;

class PostType extends AbstractCustom {
	protected $posttype;

	public function __construct( $posttype, $singular, $plural ) {
		$this->posttype = $posttype;
		$this->singular = $singular;
		$this->plural   = $plural;
	}

	public function run() {
//		if ( $this->capability_type === 'post' ) {
		if ( $this->capObj instanceof Capabilities ) {
			$this->capObj->map( $this->posttype, $this->capabilities()->capabilities, $this->roles )->run();
		}
//		}
		add_action( 'init', [ $this, 'create' ] );
	}

	public function create() {
		$args = $this->mapArgs();
		register_post_type( $this->posttype, $args );
	}

	protected function mapArgs() : array {
		$args       = [];
		$unfiltered = array_merge( $this->args, $this->labels()->labels, $this->capabilities()->capabilities, [ $this->capability_type ] );
		foreach ( $unfiltered as $key => $arg ) {
			if ( is_array( $arg ) ) {
				$arg = array_unique( $arg );
			}
			$args[ $key ] = $arg;
		}

		return $args;
	}

	protected function defaultCaps() : array {
		return [
			'capabilities' => [
				// meta caps
				'edit_post'              => 'edit_' . $this->posttype,
				'read_post'              => 'read_' . $this->posttype,
				'delete_post'            => 'delete_' . $this->posttype,
				// primitive
				'edit_posts'             => 'edit_' . $this->posttype . 's',
				'edit_others_posts'      => 'edit_others_' . $this->posttype . 's',
				'read_private_posts'     => 'read_private_' . $this->posttype,
				'publish_posts'          => 'publish_' . $this->posttype . 's',
				// additional primitive
				'read'                   => 'read',
				'delete_posts'           => 'delete_' . $this->posttype . 's',
				'delete_private_posts'   => 'delete_private_' . $this->posttype . 's',
				'delete_published_posts' => 'delete_published_' . $this->posttype . 's',
				'delete_others_posts'    => 'delete_others_' . $this->posttype . 's',
				'edit_private_posts'     => 'edit_private_' . $this->posttype . 's',
				'edit_published_posts'   => 'edit_published_' . $this->posttype . 's',
				'create_posts'           => 'create_' . $this->posttype . 's',
			],
		];
	}

	protected function defaultLabels() : array {
		return [
			'labels' => [
				'name'               => sprintf( _x( '%s', 'General CPT Name', 'alpipego-wplib' ), $this->plural ),
				'singular_name'      => sprintf( _x( '%s', 'Singular CPT Name', 'alpipego-wplib' ), $this->singular ),
				'add_new'            => __( 'Add New', 'alpipego-wplib' ),
				'add_new_item'       => sprintf( __( 'Add new %s', 'alpipego-wplib' ), $this->singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'alpipego-wplib' ), $this->singular ),
				'new_item'           => sprintf( __( 'New %s', 'alpipego-wplib' ), $this->singular ),
				'view_item'          => sprintf( __( 'View %s', 'alpipego-wplib' ), $this->singular ),
				'search_items'       => sprintf( __( 'Search %s', 'alpipego-wplib' ), $this->plural ),
				'not_found'          => sprintf( __( 'No %s found', 'alpipego-wplib' ), $this->plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'alpipego-wplib' ), $this->plural ),
				'parent_item_colon'  => sprintf( __( 'Parent: %s', 'alpipego-wplib' ), $this->singular ),
				'all_items'          => sprintf( __( 'All %s', 'alpipego-wplib' ), $this->plural ),
				'archives'           => sprintf( __( '%s Archive', 'alpipego-wplib' ), $this->singular ),
				'menu_name'          => sprintf( _x( '%s', 'Menu Name', 'alpipego-wplib' ), $this->plural ),
				'name_admin_bar'     => sprintf( _x( '%s', 'Admin Bar Name', 'alpipego-wplib' ), $this->singular ),
			],
		];
	}
}
