<?php

/**
 * CarnavalSF Theme Functions
 *
 * This file contains all the theme setup functions and customizer options
 * for the CarnavalSF WordPress theme.
 */

// Register navigation menus
function carnavalsf_register_menus() {
  register_nav_menus([
    'header-1' => __('Header Menu 1', 'carnavalsf'),
    'header-2' => __('Header Menu 2', 'carnavalsf')
  ]);
}
add_action('init', 'carnavalsf_register_menus');

// Enqueue theme styles and scripts
function carnavalsf_enqueue_assets() {

  // Main stylesheet
  wp_enqueue_style('carnavalsf-style', get_stylesheet_uri());

  // Google Fonts
  wp_enqueue_style('carnavalsf-fonts', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&family=Saira+Condensed:wght@400;800&display=block');

  // Menu script
  wp_enqueue_script('carnavalsf-menu', get_template_directory_uri() . '/js/menu.js', ['jquery'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'carnavalsf_enqueue_assets');

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

  // Body Font
  $wp_customize->add_setting('body_font', [
    'default' => 'Lato',
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

  // Heading Size Controls
  $headings = ['h1' => '6.25rem', 'h2' => '3.5rem', 'h3' => '2.5rem',
               'h4' => '1.75rem', 'h5' => '1.25rem', 'h6' => '1rem'];

  foreach ($headings as $heading => $default) {
    $wp_customize->add_setting("{$heading}_size", [
      'default' => $default,
      'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control("{$heading}_size", [
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
  echo ':root {';

  // Output color variables
  $colors = [
    'accent-1' => get_theme_mod('accent_color_1', '#FFA843'),
    'accent-2' => get_theme_mod('accent_color_2', '#9C286E'),
    'accent-3' => get_theme_mod('accent_color_3', '#05DFD7'),
    'dark-text' => get_theme_mod('dark_text_color', '#383838'),
    'light-text' => get_theme_mod('light_text_color', '#FFFFFF'),
  ];

  foreach ($colors as $name => $value) {
    echo "--{$name}: {$value};";
  }

  // Output typography variables
  echo '--body-font: "' . get_theme_mod('body_font', 'Lato') . '", sans-serif;';
  echo '--accent-font: "' . get_theme_mod('accent_font', 'Saira Condensed') . '", sans-serif;';

  // Output heading sizes
  $headings = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
  foreach ($headings as $heading) {
    $size = get_theme_mod("{$heading}_size");
    if ($size) {
      echo "--{$heading}-size: {$size};";
    }
  }

  echo '}';
  echo '</style>';
}
add_action('wp_head', 'carnavalsf_customizer_css');
