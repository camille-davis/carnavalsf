<?php
/**
 * Theme helper functions
 *
 * @package CarnavalSF
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the theme version.
 *
 * @return string Theme version.
 */
function carnavalsf_get_theme_version() {
	static $version = null;
	if ( null === $version ) {
		$version = wp_get_theme()->get( 'Version' );
	}
	return $version;
}

