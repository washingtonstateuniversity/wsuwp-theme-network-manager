<?php namespace WSUWP\Theme\Network_Manager;

/**
 * Adds Plugins API endpoint to Network Manager.
 * Requests should take the form https://domain.wsu.edu/wp-json/wsuwp-network-manager/v1/plugins/
 *
 * @since 0.0.1
 *
 * @link URL that provides more information
 * @see: Reference to a function, method, class, or super class that is heavily-relied on within.
 *
 *
*/
class Update_API {


	/**
	 * Init the module
	 *
	 * @since 0.0.1
	*/
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'register_endpoint' ) );

	}

	/**
	 * Register the new endpoint in WP
	 *
	 * @since 0.0.1
	*/
	public function register_endpoint() {

		register_rest_route(
			'wsuwp-network-manager/v1',
			'/updates',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'the_response' ),
			)
		);
	} // End register_endpoint


	/**
	 * Build output for api response.
	 *
	 * @param WP_REST_Request $request Request to the api.
	 *
	 * @since 0.0.1
	*/
	public function the_response( $request ) {

		$updates = array(
			'platform' => $this->get_platform(),
			'plugins'  => $this->get_plugins(),
			'themes'   => $this->get_themes(),
		);

		$json = wp_json_encode( $updates );

		echo $json;

		die();

	} // End the_response


	/**
	 * Get array of installed plugins
	 *
	 * @since 0.0.1
	 *
	 * @param WP_REST_Request $request Request to the api.
	 *
	 * @return array Array of installed plugins.
	*/
	protected function get_plugins() {

		/**  Check if get_plugins() function exists. This is required on the front end of the
		 * site, since it is in a file that is normally only loaded in the admin. */
		if ( ! function_exists( 'get_plugins' ) ) {

			require_once ABSPATH . 'wp-admin/includes/plugin.php';

		} // ENd if

		// Get all installed plugins
		$installed_plugins = get_plugins();

		// Check if installed plugins exists and is an array
		if ( empty( $installed_plugins ) || ! is_array( $installed_plugins ) ) {

			// If empty or isn't an array return nothing.
			return array();

		} else {

			// We'll add to this later
			$plugins = array();

			// Loop through all installed plugins
			foreach ( $installed_plugins as $file_path => $plugin ) {

				// Split the path by directory
				$plugin_path_array = explode( '/', $file_path );

				// Get the plugin folder name
				$plugin_key = strtolower( reset( $plugin_path_array ) );

				// Let's reformat this a bit to be what's expected
				$installed_plugin = array(
					'key'      => $plugin_key,
					'version'  => $plugin['Version'],
					'title'    => $plugin['Name'],
					'desc'     => $plugin['Description'],
				);

				// Add to plugins array
				$plugins[ $plugin_key ] = $installed_plugin;

			} // End foreach

			return $plugins;

		} // End if

	} // End get_installed_plugins_array


	protected function get_themes() {

		$installed_themes = wp_get_themes();

		$themes = array();

		foreach ( $installed_themes as $theme_key => $installed_theme ) {

			if ( ! empty( $installed_theme && is_object( $installed_theme ) ) ) {

				// Let's reformat this a bit to be what's expected
				$theme = array(
					'key'      => $theme_key,
					'version'  => $installed_theme->get( 'Version' ),
					'title'    => $installed_theme->get( 'Name' ),
					'desc'     => $installed_theme->get( 'Description' ),
					'icon'     => $installed_theme->get_screenshot(),
				);

				$themes[ $theme_key ] = $theme;

			} // End if
		} // End foreach

		return $themes;

	}


	protected function get_platform() {

		$platform = array(
			'version' => get_bloginfo( 'version' ),
		);

		return $platform;

	}

}
