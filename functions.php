<?php

/**
 * CarnavalSF Theme Functions
 */

// Development only - don't append version to style.css and script.js
// TODO: Remove in prod.
function carnavalsf_remove_version_scripts_styles($src) {
  if (strpos($src, 'ver=')) {
    $src = remove_query_arg('ver', $src);
  }
  return $src;
}
add_filter('style_loader_src', 'carnavalsf_remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'carnavalsf_remove_version_scripts_styles', 9999);

// Load customizer functions.
require_once get_template_directory() . '/inc/customizer.php';

// Add theme support for website logo.
add_theme_support( 'custom-logo' );

// Add theme support for featured images.
add_theme_support( 'post-thumbnails' );

// Register navigation menus.
function carnavalsf_register_menus() {
  register_nav_menus([
    'header-1' => __('Header Menu 1', 'carnavalsf'),
    'header-2' => __('Header Menu 2', 'carnavalsf')
  ]);
}
add_action('init', 'carnavalsf_register_menus');

// Register footer widget areas.
function carnavalsf_widgets_init() {
  register_sidebar([
      'name'          => __('Footer Column 1', 'carnavalsf'),
      'id'            => 'footer-column-1',
      'before_widget' => '<div class="footer-widget">',
      'after_widget'  => '</div>',
  ]);
  register_sidebar([
      'name'          => __('Footer Column 2', 'carnavalsf'),
      'id'            => 'footer-column-2',
      'before_widget' => '<div class="footer-widget">',
      'after_widget'  => '</div>',
  ]);
  register_sidebar([
      'name'          => __('Footer Column 3', 'carnavalsf'),
      'id'            => 'footer-column-3',
      'before_widget' => '<div class="footer-widget">',
      'after_widget'  => '</div>',
  ]);
  register_sidebar([
      'name'          => __('Footer Bottom', 'carnavalsf'),
      'id'            => 'footer-bottom',
      'before_widget' => '<div class="footer-widget">',
      'after_widget'  => '</div>',
  ]);
}
add_action('widgets_init', 'carnavalsf_widgets_init');

// Enqueue theme styles and scripts
function carnavalsf_enqueue_assets() {

  // Main stylesheet
  wp_enqueue_style('carnavalsf-style', get_stylesheet_uri());

  // Menu script
  wp_enqueue_script(
    'carnavalsf-menu',
    get_template_directory_uri() . '/js/menu.js',
    array('jquery'),
    '1.0',
    true
  );
}
add_action('wp_enqueue_scripts', 'carnavalsf_enqueue_assets');

// Disable automatic image resizing
function carnavalsf_disable_image_resizing() {
  add_filter('intermediate_image_sizes_advanced', '__return_empty_array');
}
add_action('init', 'carnavalsf_disable_image_resizing');

// Customize Details block with h3 tags and expand icon.
add_filter('render_block', 'customize_details_block', 10, 2);
function customize_details_block($block_content, $block) {
    if ($block['blockName'] === 'core/details') {
        $block_content = str_replace(
            '<summary>',
            '<summary><div class="expand-icon"><img src="/wp-content/themes/carnavalsf/img/caret-right-cropped.png" alt="" /></div><h3>',
            $block_content
        );
        $block_content = str_replace(
            '</summary>',
            '</h3></summary>',
            $block_content
        );
    }
    return $block_content;
}
