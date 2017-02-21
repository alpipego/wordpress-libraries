<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 12/11/2016
 * Time: 12:50
 */

namespace Alpipego\WpLib\Custom;


class Taxonomy extends AbstractCustom {
	protected $taxonomy;
	protected $objects;

	public function __construct( string $taxonomy, string $singular, string $plural, array $objects ) {
		$this->taxonomy = $taxonomy;
		$this->singular = $singular;
		$this->plural   = $plural;
		$this->objects  = $objects;
	}

	public function register() {
		$args = array_merge( $this->args, $this->labels()->labels, $this->capabilities()->capabilities );
		register_taxonomy( $this->taxonomy, $this->objects, $args );
	}

	public function run() {
		if ( $this->capability_type === 'post' ) {
			$this->capObj->map( $this->taxonomy, $this->capabilities()->capabilities, $this->roles )->run();
		}
		add_action( 'init', [ $this, 'register' ] );
	}

	protected function defaultCaps() : array {
		return [
			'capabilities' => [
				'manage_terms' => 'manage_' . $this->taxonomy,
				'edit_terms'   => 'edit_' . $this->taxonomy,
				'delete_terms' => 'delete_' . $this->taxonomy,
				'assign_terms' => 'assign_' . $this->taxonomy,
			],
		];
	}

	protected function defaultLabels() : array {
		return [
			'labels' => [
				'name'                       => sprintf( _x( '%s', 'Taxonomy General Name', 'alpipego-wplib' ), $this->plural ),
				'singular_name'              => sprintf( _x( '%s', 'Taxonomy Singular Name', 'alpipego-wplib' ), $this->singular ),
				'search_items'               => sprintf( __( 'Search %s', 'alpipego-wplib' ), $this->plural ),
				'popular_items'              => sprintf( __( 'Popular %s', 'alpipego-wplib' ), $this->plural ),
				'all_items'                  => sprintf( __( 'All %s', 'alpipego-wplib' ), $this->plural ),
				'parent_item'                => sprintf( __( 'Parent %s', 'alpipego-wplib' ), $this->singular ),
				'parent_item_colon'          => sprintf( __( 'Parent %s:', 'alpipego-wplib' ), $this->singular ),
				'edit_item'                  => sprintf( __( 'Edit %s', 'alpipego-wplib' ), $this->singular ),
				'update_item'                => sprintf( __( 'Update %s', 'alpipego-wplib' ), $this->singular ),
				'add_new_item'               => sprintf( __( 'Add New %s', 'alpipego-wplib' ), $this->singular ),
				'new_item_name'              => sprintf( __( 'New %s Name', 'alpipego-wplib' ), $this->singular ),
				'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'alpipego-wplib' ), $this->plural ),
				'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'alpipego-wplib' ), $this->plural ),
				'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'alpipego-wplib' ), $this->plural ),
				'not_found'                  => sprintf( __( 'No %s found.', 'alpipego-wplib' ), $this->plural ),
				'menu_name'                  => sprintf( _x( '%s', 'Taxonomy Menu Name', 'alpipego-wplib' ), $this->plural ),
			]
		];
	}
}
