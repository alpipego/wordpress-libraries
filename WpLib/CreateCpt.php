<?php

namespace Alpipego\WpLib;

class CreateCpt {
	protected $posttype;
	protected $singular;
	protected $plural;
	protected $icon;
	protected $role;
	protected $supports;
	protected $features;
	protected $rewrite;
	protected $queryVar;

	public function __construct(
		$posttype, $singular, $plural, $icon = false, $supports = [
		'title',
		'editor',
		'author',
		'thumbnail',
		'revisions'
	], $role = 'administrator', $rewrite = false, $queryVar = false, $features = []
	) {
		$this->posttype = $posttype;
		$this->singular = $singular;
		$this->plural   = $plural;
		$this->icon     = $icon ? $icon : 'dashicons-edit';
		$this->supports = $supports;
		$this->role     = $role;
		$this->rewrite  = $rewrite ? (array) $rewrite : [ 'slug' => $posttype ];
		$this->features = $features;
		$this->queryVar = $queryVar ? $queryVar : $posttype;
	}

	public function run() {
		\add_action( 'init', [ $this, 'createCpt' ] );
		\add_action( 'init', [ $this, 'mapMetaCaps' ], 11 );
	}

	public function createCpt() {
		$args = $this->support();

		$args['label']           = $this->posttype;
		$args['labels']          = $this->labels( $this->singular, $this->plural );
		$args['capability_type'] = $this->posttype;
		$args['capabilities']    = $this->capabilities( $this->posttype );
		$args['menu_icon']       = $this->icon;
		$args['rewrite']         = $this->rewrite();
		$args['query_var']       = $this->queryVar;

		\register_post_type( $this->posttype, $args );
		$this->addCaps();
	}

	public function support() {
		return array_merge([
			'supports'            => $this->supports,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,

		], $this->features);
	}

	public function labels( $singular, $plural ) {
		return [
			'name'               => $plural,
			'singular_name'      => $singular,
			'menu_name'          => $singular,
			'parent_item_colon'  => 'Parent ' . $singular . ':',
			'all_items'          => 'All ' . $plural,
			'view_item'          => 'View ' . $singular,
			'add_new_item'       => 'Add New ' . $singular,
			'add_new'            => 'New ' . $singular,
			'edit_item'          => 'Edit ' . $singular,
			'update_item'        => 'Update ' . $singular,
			'search_items'       => 'Search ' . $plural,
			'not_found'          => 'No ' . $plural . ' found',
			'not_found_in_trash' => 'No ' . $plural . ' found in Trash',
		];
	}

	public function capabilities( $posttype ) {
		return [
			'read_post'              => 'read_' . $posttype,
			'read_private_posts'     => 'read_private_' . $posttype,
			'publish_posts'          => 'publish_' . $posttype . 's',
			'edit_posts'             => 'edit_' . $posttype . 's',
			'edit_post'              => 'edit_' . $posttype,
			'edit_others_posts'      => 'edit_others_' . $posttype . 's',
			'edit_published_posts'   => 'edit_published_' . $posttype . 's',
			'delete_posts'           => 'delete_' . $posttype . 's',
			'delete_post'            => 'delete_' . $posttype,
			'delete_published_posts' => 'delete_published_' . $posttype . 's',
			'delete_others_posts'    => 'delete_others_' . $posttype . 's',
			'comment_' . $posttype . 's',
			'read_comments_' . $posttype . 's',
			'read_' . $posttype . 's',
		];
	}

	protected function rewrite() {
		return [
			'slug'       => $this->rewrite['slug'],
			'feeds'      => isset( $this->rewrite['feeds'] ) ? $this->rewrite['feeds'] : false,
			'with_front' => isset( $this->rewrite['with_front'] ) ? $this->rewrite['with_front'] : false,
		];
	}

	public function addCaps() {
		$role = \get_role( $this->role );
		foreach ( $this->capabilities( $this->posttype ) as $new_cap ) {
			if ( ! empty( $new_cap ) && ! $role->has_cap( $new_cap ) ) {
				$role->add_cap( $new_cap );
			}
		}
	}

	public function mapMetaCaps() {
		\add_filter( 'map_meta_cap', function ( $caps, $cap, $user_id, $args ) {
			$cap_type = $this->posttype;

			/* If editing, deleting, or reading a cpt, get the post and post type object. */
			if ( 'edit_' . $cap_type == $cap || 'delete_' . $cap_type == $cap || 'read_' . $cap_type == $cap ) {
				$post      = \get_post( $args[0] );
				$post_type = \get_post_type_object( $post->post_type );

				/* Set an empty array for the caps. */
				$caps = array();
			}

			/* If editing a cpt, assign the required capability. */
			if ( 'edit_' . $cap_type == $cap ) {
				if ( $user_id == $post->post_author ) {
					$caps[] = $post_type->cap->edit_posts;
				} else {
					$caps[] = $post_type->cap->edit_others_posts;
				}
			} /* If deleting a cpt, assign the required capability. */
			elseif ( 'delete_' . $cap_type == $cap ) {
				if ( $user_id == $post->post_author ) {
					$caps[] = $post_type->cap->delete_posts;
				} else {
					$caps[] = $post_type->cap->delete_others_posts;
				}
			} /* If reading a private cpt, assign the required capability. */
			elseif ( 'read_' . $cap_type == $cap ) {

				if ( 'private' != $post->post_status ) {
					$caps[] = 'read';
				} elseif ( $user_id == $post->post_author ) {
					$caps[] = 'read';
				} else {
					$caps[] = $post_type->cap->read_private_posts;
				}
			}

			/* Return the capabilities required by the user. */

			return $caps;
		}, 10, 4 );
	}

}
