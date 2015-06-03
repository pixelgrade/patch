<?php
/*
 * This hook is called only when the theme gets activated and it validates your license.
 * It also provides you future premium features and we recommend you to keep it this way.
 */
function pixelgrade_activate_patch() {
	/**
	 * Some settings needed for the first activation
	 */
	$theme_name = 'patch';

	// try to get the current license if there is one
	$license_key = get_option('patch_license_key');

	// when this is empty, try to get it from the license_key.txt file
	// usually this happens only the first time
	if ( empty($license_key) && file_exists(get_template_directory() . '/license_key.txt' )) {
		$license_key = file_get_contents( get_template_directory() . '/license_key.txt');
	}

	$url = 'http://themesapi.pixelgrade.com/wp-json/api/licenses/activate_theme';

	$request_args = array(
		'method' => 'GET',
		'blocking' => true,
		'body' => array(
			'license' =>  $license_key,
			'site_name' => get_bloginfo(),
			'site_url' => site_url(),
			'domain' => $_SERVER['HTTP_HOST'],
			'theme_name' => $theme_name,
		),
	);

	// now send all these args to pixelgrade API and the server will return the valid data(license, typekit_id or fonts list)
	$response_body = wp_remote_retrieve_body( wp_safe_remote_post( $url , $request_args) );

	$response_array = json_decode($response_body, true);

	if ( isset( $response_array['status'] ) && $response_array['status'] === 'failed' ) {
		return;
	}

	if ( isset( $response_array['valid_license'] ) && ! empty( $response_array['valid_license'] ) ) {
		update_option( 'patch_license_key', $response_array['valid_license'] );
	}

	return;
}

add_action( 'after_switch_theme', 'pixelgrade_activate_patch' ); ?>