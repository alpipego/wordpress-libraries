<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 25/07/16
 * Time: 15:10
 */

namespace Alpipego\WpLib\Options;


/**
 * Class AbstractOption
 * @package Alpipego\WpLib
 */
abstract class AbstractOption {
	/**
	 * @var array
	 */
	public $optionsField = [
		'id'    => null,
		'title' => null,
	];
	/**
	 * @var string
	 */
	protected $viewsPath;
	/**
	 * @var
	 */
	protected $optionsPage;
	/**
	 * @var
	 */
	protected $optionsGroup;

	/**
	 * AbstractOption constructor.
	 *
	 * @param string $page page identifier
	 * @param array $section section id and name
	 * @param string $pluginPath plugin base path
	 */
	public function __construct( string $page, array $section, string $pluginPath ) {
		$this->viewsPath    = $pluginPath . 'views/';
		$this->optionsPage  = $page;
		$this->optionsGroup = $section;
	}

	/**
	 *
	 */
	public function run() {
		\add_action( 'admin_init', [ $this, 'addField' ] );
		\add_action( 'admin_init', [ $this, 'registerSetting' ] );
	}

	/**
	 *
	 */
	public function registerSetting() {
		\register_setting( $this->optionsGroup['id'], $this->optionsField['id'], [
			'sanitize_callback' => [
				$this,
				'sanitize',
			],

		] );
	}

	/**
	 *
	 */
	public function addField() {
		\add_settings_field( $this->optionsField['id'], $this->optionsField['title'], [
			$this,
			'callback',
		], $this->optionsPage, $this->optionsGroup['id'], ! empty( $this->optionsField['args'] ) ? $this->optionsField['args'] : [] );
	}

	/**
	 * @param $name
	 * @param $args
	 */
	protected function includeView( $name, $args ) {
		$fileArr = preg_split( '/(?=[A-Z-_])/', $name );
		$fileArr = array_map( function ( &$value ) {
			return trim( $value, '-_' );
		}, $fileArr );
		$fileArr = array_map( 'strtolower', $fileArr );

		include $this->viewsPath . 'field/' . implode( '-', $fileArr ) . '.php';
	}
}
