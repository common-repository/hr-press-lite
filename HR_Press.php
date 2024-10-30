<?php
/**
 * Plugin Name: Hr Press Lite
 * Plugin URI: https://CodeClove.com/hr-press
 * Description: Hr Press Lite is the best HR Management wordPress Plugin for managing the Human resources (HR) refers to the department or function within a company that is responsible for managing and overseeing the administration of the organization's employees. you can manage the employee holidays and checkin and checkout times so much more with hr press lite plugin.
 * Version: 1.0.1
 * Author: CodeClove
 * Author URI: https://CodeClove.com/
 * Text Domain: hrp
 * Requires at least: 5.5
 * Requires PHP: 7.0
 */

defined( 'ABSPATH' ) || exit;
define( 'HRP_PLUGIN_VERSION', '1.0.1' );
define( 'HRP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'HRP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HRP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/** It's a singleton class that loads the admin and public classes. */
final class HR_Press {
	/**
	 * Instance
	 *
	 * @access private
	 * @var object
	 */
	private static $instance = null;

	/**
	 * This function initializes the class and sets up the database.
	 */
	private function __construct() {
		$this->init();
		$this->setup_database();
	}

	/**
	 * If the instance is null, create a new instance of the class.
	 *
	 * @return The instance of the class.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * If the user is an admin, load the admin.php file, otherwise load the public.php file.
	 */
	private function init() {
		require_once HRP_PLUGIN_DIR . 'includes/constants.php';
		if ( is_admin() ) {
			require_once HRP_PLUGIN_DIR . 'admin/admin.php';
		}
		require_once HRP_PLUGIN_DIR . 'public/public.php';
	}

	/**
	 * This function is called when the plugin is activated and deactivated.
	 */
	private function setup_database() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/database.php';
		register_activation_hook( __FILE__, array( 'HRP_Database', 'activation' ) );
		register_deactivation_hook( __FILE__, array( 'HRP_Database', 'deactivation' ) );
	}
}
HR_Press::get_instance();
