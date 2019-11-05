<?php namespace WSUWP\Theme\Network_Manager;

/**
 * Manager class for network theme.
 *
 * @since 0.0.1
 *
 * @link URL that provides more information
 * @see: Reference to a function, method, class, or super class that is heavily-relied on within.
 *
 *
*/
class Network_Manager_Theme {

	/**
	 * Current theme version
	 *
	 * @since 0.0.0
	 * @var string $version.
	*/
	protected $version = '0.0.1';

	public function __construct() {

		require_once __DIR__ . '/class-plugins-api.php';

	} // End __construct


	/**
	 * Get the current theme version
	 *
	 * @since 0.0.1
	 *
	 * @return string Theme version.
	*/
	public static function get_theme_version() {
		return $this->$version;
	}


	/**
	 * Load the theme
	 *
	 * @since 0.0.1
	*/
	public function init() {

		$plugins_api = new Plugins_API();
		$plugins_api->init();

	}
}
