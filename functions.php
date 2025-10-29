<?php

/**
 * CarnavalSF Theme Functions
 */

// Add theme support for website logo.
add_theme_support( 'custom-logo' );

// Register navigation menus
function carnavalsf_register_menus() {
  register_nav_menus([
    'header-1' => __('Header Menu 1', 'carnavalsf'),
    'header-2' => __('Header Menu 2', 'carnavalsf')
  ]);
}
add_action('init', 'carnavalsf_register_menus');

// Register footer widget area
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

// Add customizer controls.
function carnavalsf_customize_controls() {
  global $wp_customize;

  // Enqueue customizer style.
  wp_enqueue_style(
    'carnavalsf-customizer',
    get_template_directory_uri() . '/css/customizer.css',
    array(),
    '1.0'
  );

  // Get typography setting defaults because they aren't passed to JS automatically.
  $typography_settings = [];
  $controls = $wp_customize->controls();
  foreach ($controls as $control) {
    if ($control->section !== 'carnavalsf_typography') {
      continue;
    }
    $typography_settings[] = $control->id;
  }

  // Add the defaults to the customize-controls script.
  $inline_script = '';
  foreach ($typography_settings as $setting_id) {
      $setting = $wp_customize->get_setting($setting_id);
      if ($setting) {
          $inline_script .= sprintf(
              'wp.customize("%s", function(setting) { setting.default = %s; });',
              $setting_id,
              wp_json_encode($setting->default)
          );
      }
  }
  wp_add_inline_script('customize-controls', $inline_script);

  // Add 'Reset' buttons.
  wp_enqueue_script(
    'carnavalsf-customizer',
    get_template_directory_uri() . '/js/customizer.js',
    array('jquery', 'customize-controls'),
    '1.0',
    true
  );
  wp_localize_script('carnavalsf-customizer', 'carnavalsfCustomizer', [
      'resetText' => __('Reset', 'carnavalsf')
  ]);
}
add_action('customize_controls_enqueue_scripts', 'carnavalsf_customize_controls');

// Customizer settings
function carnavalsf_customize_register($wp_customize) {

  // Colors Section
  $wp_customize->add_section('carnavalsf_colors', [
    'title' => __('Theme Colors', 'carnavalsf'),
    'priority' => 30,
  ]);

  // Accent Color 1
  $wp_customize->add_setting('accent_color_1', [
    'default' => '#FFA843',
    'sanitize_callback' => 'sanitize_hex_color',
  ]);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color_1', [
    'label' => __('Accent Color 1', 'carnavalsf'),
    'section' => 'carnavalsf_colors',
  ]));

  // Accent Color 2
  $wp_customize->add_setting('accent_color_2', [
    'default' => '#9C286E',
    'sanitize_callback' => 'sanitize_hex_color',
  ]);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color_2', [
    'label' => __('Accent Color 2', 'carnavalsf'),
    'section' => 'carnavalsf_colors',
  ]));

  // Accent Color 3
  $wp_customize->add_setting('accent_color_3', [
    'default' => '#05DFD7',
    'sanitize_callback' => 'sanitize_hex_color',
  ]);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color_3', [
    'label' => __('Accent Color 3', 'carnavalsf'),
    'section' => 'carnavalsf_colors',
  ]));

  // Dark Text Color
  $wp_customize->add_setting('dark_text_color', [
    'default' => '#383838',
    'sanitize_callback' => 'sanitize_hex_color',
  ]);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_text_color', [
    'label' => __('Dark Text Color', 'carnavalsf'),
    'section' => 'carnavalsf_colors',
  ]));

  // Light Text Color
  $wp_customize->add_setting('light_text_color', [
    'default' => '#FFFFFF',
    'sanitize_callback' => 'sanitize_hex_color',
  ]);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'light_text_color', [
    'label' => __('Light Text Color', 'carnavalsf'),
    'section' => 'carnavalsf_colors',
  ]));

  // Typography Section
  $wp_customize->add_section('carnavalsf_typography', [
    'title' => __('Typography', 'carnavalsf'),
    'priority' => 35,
  ]);

  // Fonts URL
  $wp_customize->add_setting('fonts_url', [
    'default' => 'https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&family=Saira+Condensed:wght@400;800&display=block',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('fonts_url', [
    'label' => __('Fonts URL', 'carnavalsf'),
    'section' => 'carnavalsf_typography',
    'type' => 'text',
  ]);

  // Body Font
  $wp_customize->add_setting('body_font', [
    'default' => 'Quicksand',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('body_font', [
    'label' => __('Body Font', 'carnavalsf'),
    'section' => 'carnavalsf_typography',
    'type' => 'text',
  ]);

  // Accent Font
  $wp_customize->add_setting('accent_font', [
    'default' => 'Saira Condensed',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('accent_font', [
    'label' => __('Accent Font', 'carnavalsf'),
    'section' => 'carnavalsf_typography',
    'type' => 'text',
  ]);

  // Body Font Size
  $wp_customize->add_setting('body_font_size', [
    'default' => '1rem',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('body_font_size', [
    'label' => __('Body Font Size', 'carnavalsf'),
    'section' => 'carnavalsf_typography',
    'type' => 'text',
  ]);

  // Heading Font Sizes
  $headings = [
    'h1' => '6.25rem',
    'h2' => '3.5rem',
    'h3' => '2.5rem',
    'h4' => '1.75rem',
    'h5' => '1.25rem',
    'h6' => '1rem',
  ];
  foreach ($headings as $heading => $default) {
    $wp_customize->add_setting("{$heading}_font_size", [
      'default' => $default,
      'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control("{$heading}_font_size", [
      'label' => __(ucfirst($heading) . ' Size', 'carnavalsf'),
      'section' => 'carnavalsf_typography',
      'type' => 'text',
    ]);
  }
}
add_action('customize_register', 'carnavalsf_customize_register');

// Output Customizer CSS
function carnavalsf_customizer_css() {
  echo '<style type="text/css">';

  // Output font import.
  $fonts_url = get_theme_mod('fonts_url');
  if ($fonts_url) {
    echo '@import url("' . esc_url_raw($fonts_url) . '");';
  }

  // Output custom css variables.
  $custom_css_variables = [
    'accent-color-1' => get_theme_mod('accent_color_1', '#FFA843'),
    'accent-color-2' => get_theme_mod('accent_color_2', '#9C286E'),
    'accent-color-3' => get_theme_mod('accent_color_3', '#05DFD7'),
    'dark-text' => get_theme_mod('dark_text_color', '#383838'),
    'light-text' => get_theme_mod('light_text_color', '#FFFFFF'),
    'body-font' => get_theme_mod('body_font', 'Quicksand') . ', sans-serif',
    'accent-font' => get_theme_mod('accent_font', 'Saira Condensed') . ', sans-serif',
    'body-font-size' => get_theme_mod('body_font_size', '1rem'),
    'h1-font-size' => get_theme_mod('h1_font_size', '6.25rem'),
    'h2-font-size' => get_theme_mod('h2_font_size', '3.5rem'),
    'h3-font-size' => get_theme_mod('h3_font_size', '2.5rem'),
    'h4-font-size' => get_theme_mod('h4_font_size', '1.75rem'),
    'h5-font-size' => get_theme_mod('h5_font_size', '1.25rem'),
    'h6-font-size' => get_theme_mod('h6_font_size', '1rem'),
  ];
  echo ':root {';
  foreach ($custom_css_variables as $name => $value) {
    echo "--{$name}: {$value};";
  }
  echo '}';

  echo '</style>';
}
add_action('wp_head', 'carnavalsf_customizer_css');

// Disable automatic image resizing
function carnavalsf_disable_image_resizing() {
  add_filter('intermediate_image_sizes_advanced', '__return_empty_array');
}
add_action('init', 'carnavalsf_disable_image_resizing');

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
