<?php
/**
 * CarnavalSF Theme Functions
 *
 * @package CarnavalSF
 */

/**
 * Theme includes
 */
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/page-color.php';
require_once get_template_directory() . '/inc/misc-block-customizations.php';
require_once get_template_directory() . '/inc/contact-form.php';

/**
 * Theme setup
 */
function carnavalsf_setup() {
	add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'carnavalsf_setup' );


/**
 * Navigation menus
 */
function carnavalsf_register_menus() {
	register_nav_menus(
		array(
			'header-1' => __( 'Header Menu 1', 'carnavalsf' ),
			'header-2' => __( 'Header Menu 2', 'carnavalsf' ),
		)
	);
}
add_action( 'init', 'carnavalsf_register_menus' );

/**
 * Footer widget areas
 */
function carnavalsf_widgets_init() {
	$widgets = array(
		'footer-column-1' => __( 'Footer Column 1', 'carnavalsf' ),
		'footer-column-2' => __( 'Footer Column 2', 'carnavalsf' ),
		'footer-column-3' => __( 'Footer Column 3', 'carnavalsf' ),
		'footer-bottom'   => __( 'Footer Bottom', 'carnavalsf' ),
	);

	foreach ( $widgets as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '',
				'after_widget'  => '',
			)
		);
	}
}
add_action( 'widgets_init', 'carnavalsf_widgets_init' );

/**
 * Styles and scripts
 */
function carnavalsf_enqueue_assets() {

	// Main stylesheet.
	wp_enqueue_style( 'carnavalsf-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// Mobile menu functionality.
	wp_enqueue_script(
		'carnavalsf-menu',
		get_template_directory_uri() . '/js/menu.js',
		array( 'jquery' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

	// Details block animation.
	wp_enqueue_script(
		'carnavalsf-details-block',
		get_template_directory_uri() . '/js/details-block.js',
		array( 'jquery' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

}
add_action( 'wp_enqueue_scripts', 'carnavalsf_enqueue_assets' );

/**
 * Disable WordPress image resizing
 */
function carnavalsf_disable_image_resizing() {
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
}
add_action( 'init', 'carnavalsf_disable_image_resizing' );

/**
 * DEV ONLY: Remove version query strings from styles and scripts
 *
 * @param string $src The source URL.
 */
function carnavalsf_remove_version_scripts_styles( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'carnavalsf_remove_version_scripts_styles', 9999 );
add_filter( 'script_loader_src', 'carnavalsf_remove_version_scripts_styles', 9999 );
