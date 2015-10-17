<?php
/**
 * Scripts
 *
 * @package     Minecraft_Validator\Scripts
 * @since       2.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Load admin scripts
 *
 * @since       2.0.0
 * @return      void
 */
function minecraft_validator_login_scripts() {
	wp_enqueue_script( 'minecraft-validator', MINECRAFT_VALIDATOR_URL . 'assets/js/usernamerewrite.js', array( 'jquery' ), false, false );
}
add_action( 'login_head', 'minecraft_validator_login_scripts' );
add_action( 'signup_header', 'minecraft_validator_login_scripts' );