<?php
/**
 * CarnavalSF Theme Functions
 *
 * @package CarnavalSF
 */

/**
 * Theme setup & includes
 */

// Sets up theme defaults and registers support for various WordPress features.
function carnavalsf_setup() {

  // Add theme support for custom logo
  add_theme_support( 'custom-logo' );

  // Add theme support for featured images
  add_theme_support( 'post-thumbnails' );

  // Add editor styles
  add_theme_support( 'editor-styles' );
  add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'carnavalsf_setup' );

// Include required files
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/page-color.php';
require_once get_template_directory() . '/inc/blocks.php';

/**
 * Navigation & Widget Registration
 */

// Register navigation menus
function carnavalsf_register_menus() {
	register_nav_menus(
		array(
			'header-1' => __( 'Header Menu 1', 'carnavalsf' ),
			'header-2' => __( 'Header Menu 2', 'carnavalsf' ),
		)
	);
}
add_action( 'init', 'carnavalsf_register_menus' );

// Register footer widget areas
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
				'before_widget' => '<div class="footer-widget">',
				'after_widget'  => '</div>',
			)
		);
	}
}
add_action( 'widgets_init', 'carnavalsf_widgets_init' );

/**
 * Asset enqueuing
 */

// Enqueue theme styles and scripts
function carnavalsf_enqueue_assets() {

	// Main stylesheet
	wp_enqueue_style( 'carnavalsf-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// Mobile menu functionality
	wp_enqueue_script(
		'carnavalsf-menu',
		get_template_directory_uri() . '/js/menu.js',
		array( 'jquery' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

	// Details block animation
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
 * Disable unwanted WordPress features
 */

// Disable automatic image resizing
function carnavalsf_disable_image_resizing() {
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
}
add_action( 'init', 'carnavalsf_disable_image_resizing' );

/**
 * Development only
 */

// Remove version query strings from styles and scripts
function carnavalsf_remove_version_scripts_styles( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'carnavalsf_remove_version_scripts_styles', 9999 );
add_filter( 'script_loader_src', 'carnavalsf_remove_version_scripts_styles', 9999 );
