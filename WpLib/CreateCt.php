<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 12/11/2016
 * Time: 12:50
 */

namespace Alpipego\WpLib;


class CreateCt {
	private $taxonomy;
	private $singular;
	private $plural;
	private $objects = [];
	private $supports = [];
	private $rewrite = [];

	public function __construct( $taxonomy, $singular, $plural, $objects = null, $supports = [], $rewrite = [] ) {
		$this->taxonomy = $taxonomy;
		$this->singular = $singular;
		$this->plural   = $plural;
		$this->objects  = $objects;
		$this->supports = $this->supports( $supports );
		$this->rewrite  = $this->rewrite( $rewrite );
	}

	private function supports( $supports ) {
		$defaults = [
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_admin_column' => true,
		];

		return array_merge( $defaults, $supports );
	}

	private function rewrite( $rewrite ) {
		if ( ! is_array( $rewrite ) ) {
			return (bool) $rewrite;
		}

		$defaults = [
			'slug' => $this->taxonomy,
		];

		return array_merge( $defaults, $rewrite );
	}

	public function register() {
		$args            = $this->supports;
		$args['labels']  = $this->labels();
		$args['rewrite'] = $this->rewrite;

		register_taxonomy( $this->taxonomy, $this->objects, $args );
	}

	private function labels() {
		return [
			'name'                       => sprintf( _x( '%s', 'taxonomy general name', 'alpipego_create_ct' ), $this->plural ),
			'singular_name'              => sprintf( _x( '%s', 'taxonomy singular name', 'alpipego_create_ct' ), $this->singular ),
			'search_items'               => sprintf( __( 'Search %s', 'alpipego_create_ct' ), $this->plural ),
			'popular_items'              => sprintf( __( 'Popular %s', 'alpipego_create_ct' ), $this->plural ),
			'all_items'                  => sprintf( __( 'All %s', 'alpipego_create_ct' ), $this->plural ),
			'parent_item'                => sprintf( __( 'Parent %s', 'alpipego_create_ct' ), $this->singular ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'alpipego_create_ct' ), $this->singular ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'alpipego_create_ct' ), $this->singular ),
			'update_item'                => sprintf( __( 'Update %s', 'alpipego_create_ct' ), $this->singular ),
			'add_new_item'               => sprintf( __( 'Add New %s', 'alpipego_create_ct' ), $this->singular ),
			'new_item_name'              => sprintf( __( 'New %s Name', 'alpipego_create_ct' ), $this->singular ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'alpipego_create_ct' ), $this->plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'alpipego_create_ct' ), $this->plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'alpipego_create_ct' ), $this->plural ),
			'not_found'                  => sprintf( __( 'No %s found.', 'alpipego_create_ct' ), $this->plural ),
			'menu_name'                  => sprintf( __( '%s', 'alpipego_create_ct' ), $this->plural ),
		];
	}

	public function run() {
		add_action( 'init', [ $this, 'register' ], 9 );
	}
}
