<?php
/**
 * Helper functions
 *
 * @package     Minecraft_Validator\Functions
 * @since       2.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Verify an account with the MC auth server
 *
 * @since       2.0.0
 * @param       string $username The username to verify
 * @param       array $result The errors or result array
 * @return      array $result The updated errors or result array
 */
function minecraft_validator_verify_account( $username, $result ) {
	$options = array(
		'timeout' => 5,
	);

	$response = wp_remote_get( 'https://api.mojang.com/users/profiles/minecraft/' . rawurlencode( $username ), $options );

	if( is_wp_error( $response ) ) {
		$return = 'error';
	} else {
		$return = wp_remote_retrieve_response_code( $response );
	}


	// Bail if on multisite and already have an error
	if( is_multisite() && $result['errors']->get_error_message( 'user_name' ) ) {
		return $result;
	}

	if( $return != 200 ) {
		if( $return == 204 ) {
			if( is_multisite() ) {
				$result['errors']->add( 'user_name', __( 'Minecraft account is invalid.', 'minecraft-validator' ) );
			} else {
				$result->add( 'mc_error', sprintf( __( '%s Minecraft account is invalid.', 'minecraft-validator' ), '<strong>' . __( 'ERROR:', 'minecraft-validator' ) . '</strong>' ) );
			}
		} else {
			if( is_multisite() ) {
				$result['errors']->add( 'user_name', __( 'Unable to contact minecraft.net.', 'minecraft-validator' ) );
			} else {
				$result->add( 'mc_error', sprintf( __( '%s Unable to contact minecraft.net.', 'minecraft-validator' ), '<strong>' . __( 'ERROR:', 'minecraft-validator' ) . '</strong>' ) );
			}
		}
	}

	return $result;
}
