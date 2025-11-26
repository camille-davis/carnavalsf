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

/**
 * Theme support registration
 */

// Register editor color palette with accent colors from customizer
function carnavalsf_editor_color_palette() {
	$editor_color_palette = array(
		array(
			'name'  => __( 'Accent Color 1', 'carnavalsf' ),
			'slug'  => 'accent-color-1',
			'color' => get_theme_mod( 'accent_color_1', '#FFA843' ),
		),
		array(
			'name'  => __( 'Accent Color 2', 'carnavalsf' ),
			'slug'  => 'accent-color-2',
			'color' => get_theme_mod( 'accent_color_2', '#9C286E' ),
		),
		array(
			'name'  => __( 'Accent Color 3', 'carnavalsf' ),
			'slug'  => 'accent-color-3',
			'color' => get_theme_mod( 'accent_color_3', '#05DFD7' ),
		),
		array(
			'name'  => __( 'Dark Text Color', 'carnavalsf' ),
			'slug'  => 'dark-text-color',
			'color' => get_theme_mod( 'dark_text_color', '#383838' ),
		),
		array(
			'name'  => __( 'Light Text Color', 'carnavalsf' ),
			'slug'  => 'light-text-color',
			'color' => get_theme_mod( 'light_text_color', '#FFFFFF' ),
		),
	);

	if ( $editor_color_palette ) {
		add_theme_support( 'editor-color-palette', $editor_color_palette );
	}
}
add_action( 'after_setup_theme', 'carnavalsf_editor_color_palette' );

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
	register_sidebar(
		array(
			'name'          => __( 'Footer Column 1', 'carnavalsf' ),
			'id'            => 'footer-column-1',
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Column 2', 'carnavalsf' ),
			'id'            => 'footer-column-2',
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Column 3', 'carnavalsf' ),
			'id'            => 'footer-column-3',
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Bottom', 'carnavalsf' ),
			'id'            => 'footer-bottom',
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>',
		)
	);
}
add_action( 'widgets_init', 'carnavalsf_widgets_init' );

/**
 * Asset enqueuing
 */

// Enqueue theme styles and scripts
function carnavalsf_enqueue_assets() {

	// Main stylesheet
	wp_enqueue_style( 'carnavalsf-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// Menu script
	wp_enqueue_script(
		'carnavalsf-menu',
		get_template_directory_uri() . '/js/menu.js',
		array( 'jquery' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

	// Transitions script
	wp_enqueue_script(
		'carnavalsf-transitions',
		get_template_directory_uri() . '/js/transitions.js',
		array( 'jquery' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'carnavalsf_enqueue_assets' );

// Enqueue block editor assets
function carnavalsf_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'carnavalsf-group-block-fullwidth',
		get_template_directory_uri() . '/js/group-block-fullwidth.js',
		array(
			'wp-blocks',
			'wp-block-editor',
			'wp-components',
			'wp-compose',
			'wp-element',
			'wp-hooks',
		),
		wp_get_theme()->get( 'Version' ),
		true
	);

	// Enqueue page color editor script
	wp_enqueue_script(
		'carnavalsf-page-color-editor',
		get_template_directory_uri() . '/js/page-color-editor.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'carnavalsf_enqueue_block_editor_assets' );

/**
 * Editor configuration
 */

// Add CSS variables to editor canvas only (not sidebar)
function carnavalsf_add_editor_inline_css( $editor_settings ) {

	// Get the CSS variables
	$inline_css = CarnavalSF_Customizer::get_inline_css();

	if ( empty( $inline_css ) ) {
		return $editor_settings;
	}

	// Ensure styles array exists
	if ( ! isset( $editor_settings['styles'] ) ) {
		$editor_settings['styles'] = array();
	}

	// Prepend CSS variables so they're available before other styles
	// Must set isGlobalStyles to false or it will be stripped out
	array_unshift(
		$editor_settings['styles'],
		array(
			'css'            => $inline_css,
			'__unstableType' => 'theme',
			'isGlobalStyles' => false,
		)
	);

	return $editor_settings;
}
add_filter( 'block_editor_settings_all', 'carnavalsf_add_editor_inline_css', 10, 1 );

/**
 * Disable unwanted WordPress features
 */

// Disable automatic image resizing
function carnavalsf_disable_image_resizing() {
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
}
add_action( 'init', 'carnavalsf_disable_image_resizing' );

// Disable layout support in block editor
function carnavalsf_disable_layout_support( $editor_settings ) {
	$editor_settings['supportsLayout'] = false;
	return $editor_settings;
}
add_filter( 'block_editor_settings_all', 'carnavalsf_disable_layout_support', 10, 1 );

// Disable shadow support in blocks
function carnavalsf_disable_shadow_support( $args, $name ) {
	if ( ! isset( $args['supports'] ) ) {
		$args['supports'] = array();
	}
	$args['supports']['shadow'] = false;
	return $args;
}
add_filter( 'register_block_type_args', 'carnavalsf_disable_shadow_support', 10, 2 );

/**
 * Block customizations
 */

// Modify block supports: enable background images
function carnavalsf_modify_block_supports( $args, $name ) {
	if ( ! isset( $args['supports'] ) ) {
		$args['supports'] = array();
	}

	// Enable background image support
	if ( ! isset( $args['supports']['background'] ) ) {
		$args['supports']['background'] = array();
	}
	$args['supports']['background']['backgroundImage'] = true;
	$args['supports']['background']['backgroundSize'] = true;

	return $args;
}
add_filter( 'register_block_type_args', 'carnavalsf_modify_block_supports', 10, 2 );

// Customize Details Gutenberg block
function carnavalsf_customize_details_block( $block_content, $block ) {
	if ( $block['blockName'] === 'core/details' ) {

		// Add 'expand' icon and wrap summary text in h3
		$block_content = str_replace(
			'<summary>',
			'<summary><div class="expand-icon"><img src="' . esc_url( get_template_directory_uri() . '/img/caret-right-cropped.png' ) . '" alt="" /></div><h3>',
			$block_content
		);

		// Close h3 and open .details-content div after summary
		$block_content = str_replace(
			'</summary>',
			'</h3></summary><div class="details-content">',
			$block_content
		);

		// Close .details-content div before closing details tag
		$block_content = str_replace(
			'</details>',
			'</div></details>',
			$block_content
		);
	}

	return $block_content;
}
add_filter( 'render_block', 'carnavalsf_customize_details_block', 10, 2 );

// Add is-fullwidth class to Group block when fullwidth attribute is set
function carnavalsf_group_block_fullwidth( $block_content, $block ) {
	if ( $block['blockName'] === 'core/group' && ! empty( $block['attrs']['fullwidth'] ) ) {
		$block_content = preg_replace(
			'/class="([^"]*wp-block-group[^"]*)"/',
			'class="$1 is-fullwidth"',
			$block_content
		);
	}

	return $block_content;
}
add_filter( 'render_block', 'carnavalsf_group_block_fullwidth', 10, 2 );

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
