<?php
/**
 * Plugin Name:     Minecraft Validator
 * Plugin URI:      http://wordpress.org/plugins/minecraft-validator
 * Description:     Simple plugin to verify new WordPress accounts against the Minecraft user database.
 * Version:         2.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      https://section214.com
 * Text Domain:     minecraft-validator
 *
 * @package         Minecraft Validator
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( ! class_exists( 'Minecraft_Validator' ) ) {


	/**
	 * Main Minecraft_Validator class
	 *
	 * @since       2.0.0
	 */
	class Minecraft_Validator {


		/**
		 * @var         Minecraft_Validator $instance The one true Minecraft_Validator
		 * @since       1.0.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       2.0.0
		 * @return      self::$instance The one true Minecraft_Validator
		 */
		public static function instance() {
			if( ! self::$instance ) {
				self::$instance = new Minecraft_Validator();
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				self::$instance->includes();
				self::$instance->hooks();
			}

			return self::$instance;
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      public
		 * @since       2.0.0
		 * @return      void
		 */
		public function setup_constants() {
			// Plugin version
			define( 'MINECRAFT_VALIDATOR_VER', '2.0.0' );

			// Plugin path
			define( 'MINECRAFT_VALIDATOR_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin URL
			define( 'MINECRAFT_VALIDATOR_URL', plugin_dir_url( __FILE__ ) );
		}


		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       2.0.0
		 * @return      void
		 */
		private function includes() {
			require_once MINECRAFT_VALIDATOR_DIR . 'includes/scripts.php';
			require_once MINECRAFT_VALIDATOR_DIR . 'includes/functions.php';
		}


		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       2.0.0
		 * @return      void
		 */
		private function hooks() {
			if( is_multisite() ) {
				add_filter( 'wpmu_validate_user_signup', array( $this, 'verify_ms_account' ) );
			} else {
				add_filter( 'registration_errors', array( $this, 'verify_account' ), 10, 3);
			}
		}


		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'minecraft_validator_language_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), '' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'minecraft-validator', $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/minecraft-validator/' . $mofile;

			if( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/minecraft-validator/ folder
				load_textdomain( 'minecraft-validator', $mofile_global );
			} elseif( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/minecraft-validator/languages/ folder
				load_textdomain( 'minecraft-validator', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'minecraft-validator', false, $lang_dir );
			}
		}


		/**
		 * Verify MC account on registration
		 *
		 * @access      public
		 * @since       1.0.0
		 * @param       object $errors Registration errors
		 * @param       string $login The username to register
		 * @param       string $email The email address for this user
		 * @return      void
		 */
		public function verify_account( $errors, $login, $email ) {
			$result = minecraft_validator_verify_account( $login, $errors );

			return $result;
		}


		/**
		 * Verify MC account on MS registration
		 *
		 * @access      public
		 * @since       1.0.0
		 * @param       array $result The multisite registration array
		 * @return      void
		 */
		public function verify_ms_account( $result ) {
			$result = minecraft_validator_verify_account( $result['user_name'], $result );

			return $result;
		}
	}
}


/**
 * The main function responsible for returning the one true Minecraft_Validator
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      Minecraft_Validator The one true Minecraft_Validator
 */
function minecraft_validator() {
	return Minecraft_Validator::instance();
}
add_action( 'plugins_loaded', 'minecraft_validator' );