<?php
/**
 * Customizer functionality for Carnaval SF theme
 */

class CarnavalSF_Customizer
{
  public function __construct()
  {
    add_action('customize_register', [$this, 'register_controls']);
    add_action('customize_controls_enqueue_scripts', [$this, 'add_reset_buttons']);
    add_action('customize_controls_enqueue_scripts', [$this, 'add_customizer_style']);
    add_action('wp_head', [$this, 'output_inline_css']);

  }

  public function register_controls($wp_customize)
  {
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
      'h2' => '4.25rem',
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

  public function add_reset_buttons()
  {
    global $wp_customize;

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

  public function add_customizer_style()
  {
    wp_enqueue_style('carnavalsf-customizer', get_template_directory_uri() . '/css/customizer.css');
  }

  public function output_inline_css()
  {
    echo '<style type="text/css">';

    // Output font import.
    $fonts_url = get_theme_mod('fonts_url');
    if ($fonts_url) {
      echo '@import url("' . esc_url_raw($fonts_url) . '");';
    }

    // Output custom css variables.
    $custom_css_variables = [
      'accent-color-1' => get_theme_mod('accent_color_1', '#FFA843'),
      'accent-color-1-filter' => $this->hex_to_css_filter(get_theme_mod('accent_color_1', '#FFA843')),
      'accent-color-1-rgb' => implode(',', $this->hex_to_rgb(get_theme_mod('accent_color_1', '#FFA843'))),
      'accent-color-2' => get_theme_mod('accent_color_2', '#9C286E'),
      'accent-color-2-filter' => $this->hex_to_css_filter(get_theme_mod('accent_color_2', '#9C286E')),
      'accent-color-2-rgb' => implode(',', $this->hex_to_rgb(get_theme_mod('accent_color_2', '#9C286E'))),
      'accent-color-3' => get_theme_mod('accent_color_3', '#05DFD7'),
      'accent-color-3-filter' => $this->hex_to_css_filter(get_theme_mod('accent_color_3', '#05DFD7')),
      'accent-color-3-rgb' => implode(',', $this->hex_to_rgb(get_theme_mod('accent_color_3', '#05DFD7'))),
      'dark-text' => get_theme_mod('dark_text_color', '#383838'),
      'dark-text-filter' => $this->hex_to_css_filter(get_theme_mod('dark_text_color', '#383838')),
      'dark-text-rgb' => implode(',', $this->hex_to_rgb(get_theme_mod('dark_text_color', '#383838'))),
      'light-text' => get_theme_mod('light_text_color', '#FFFFFF'),
      'light-text-filter' => $this->hex_to_css_filter(get_theme_mod('light_text_color', '#FFFFFF')),
      'light-text-rgb' => implode(',', $this->hex_to_rgb(get_theme_mod('light_text_color', '#FFFFFF'))),
      'body-font' => get_theme_mod('body_font', 'Quicksand') . ', sans-serif',
      'accent-font' => get_theme_mod('accent_font', 'Saira Condensed') . ', sans-serif',
      'body-font-size' => get_theme_mod('body_font_size', '1rem'),
      'h1-font-size' => get_theme_mod('h1_font_size', '6.25rem'),
      'h2-font-size' => get_theme_mod('h2_font_size', '4.25rem'),
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

  private function hex_to_rgb($hex)
  {
    // Remove hash if present
    $hex = ltrim($hex, '#');

    // Expand shorthand hex (e.g., "03F" to "0033FF")
    if (strlen($hex) === 3) {
      $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    return [
      'r' => hexdec(substr($hex, 0, 2)),
      'g' => hexdec(substr($hex, 2, 2)),
      'b' => hexdec(substr($hex, 4, 2)),
    ];
  }

  private function hex_to_css_filter($hex)
  {
    $hex = $this->hex_to_rgb($hex);

    // Convert to RGB
    // Create color object
    $color = new FilterColor($hex['r'], $hex['g'], $hex['b']);
    $solver = new FilterSolver($color);
    $result = $solver->solve();

    return $result['filter'];
  }
}

class FilterColor
{
  public $r, $g, $b;

  public function __construct($r, $g, $b)
  {
    $this->set($r, $g, $b);
  }

  public function set($r, $g, $b)
  {
    $this->r = $this->clamp($r);
    $this->g = $this->clamp($g);
    $this->b = $this->clamp($b);
  }

  public function clamp($value)
  {
    return max(0, min(255, $value));
  }

  public function invert($value = 1)
  {
    $this->r = ($value + ($this->r / 255) * (1 - 2 * $value)) * 255;
    $this->g = ($value + ($this->g / 255) * (1 - 2 * $value)) * 255;
    $this->b = ($value + ($this->b / 255) * (1 - 2 * $value)) * 255;
  }

  public function sepia($value = 1)
  {
    $this->multiply([
      0.393 + 0.607 * (1 - $value),
      0.769 - 0.769 * (1 - $value),
      0.189 - 0.189 * (1 - $value),
      0.349 - 0.349 * (1 - $value),
      0.686 + 0.314 * (1 - $value),
      0.168 - 0.168 * (1 - $value),
      0.272 - 0.272 * (1 - $value),
      0.534 - 0.534 * (1 - $value),
      0.131 + 0.869 * (1 - $value),
    ]);
  }

  public function saturate($value = 1)
  {
    $this->multiply([
      0.213 + 0.787 * $value,
      0.715 - 0.715 * $value,
      0.072 - 0.072 * $value,
      0.213 - 0.213 * $value,
      0.715 + 0.285 * $value,
      0.072 - 0.072 * $value,
      0.213 - 0.213 * $value,
      0.715 - 0.715 * $value,
      0.072 + 0.928 * $value,
    ]);
  }

  public function hueRotate($angle = 0)
  {
    $angle = deg2rad($angle);
    $sin = sin($angle);
    $cos = cos($angle);

    $this->multiply([
      0.213 + $cos * 0.787 - $sin * 0.213,
      0.715 - $cos * 0.715 - $sin * 0.715,
      0.072 - $cos * 0.072 + $sin * 0.928,
      0.213 - $cos * 0.213 + $sin * 0.143,
      0.715 + $cos * 0.285 + $sin * 0.14,
      0.072 - $cos * 0.072 - $sin * 0.283,
      0.213 - $cos * 0.213 - $sin * 0.787,
      0.715 - $cos * 0.715 + $sin * 0.715,
      0.072 + $cos * 0.928 + $sin * 0.072,
    ]);
  }

  public function brightness($value = 1)
  {
    $this->linear($value);
  }

  public function contrast($value = 1)
  {
    $this->linear($value, - (0.5 * $value) + 0.5);
  }

  public function linear($slope = 1, $intercept = 0)
  {
    $this->r = $this->clamp($this->r * $slope + $intercept * 255);
    $this->g = $this->clamp($this->g * $slope + $intercept * 255);
    $this->b = $this->clamp($this->b * $slope + $intercept * 255);
  }

  public function multiply($matrix)
  {
    $newR = $this->clamp(
      $this->r * $matrix[0] + $this->g * $matrix[1] + $this->b * $matrix[2]
    );
    $newG = $this->clamp(
      $this->r * $matrix[3] + $this->g * $matrix[4] + $this->b * $matrix[5]
    );
    $newB = $this->clamp(
      $this->r * $matrix[6] + $this->g * $matrix[7] + $this->b * $matrix[8]
    );
    $this->r = $newR;
    $this->g = $newG;
    $this->b = $newB;
  }
}

class FilterSolver
{
  private $target;
  private $reusedColor;

  public function __construct($target)
  {
    $this->target = $target;
    $this->reusedColor = new FilterColor(0, 0, 0);
  }

  public function solve()
  {
    $result = $this->solveNarrow($this->solveWide());
    return [
      'values' => $result['values'],
      'loss' => $result['loss'],
      'filter' => $this->css($result['values'])
    ];
  }

  private function solveWide()
  {
    $A = 5;
    $c = 15;
    $a = [60, 180, 18000, 600, 1.2, 1.2];

    $best = ['loss' => INF];
    for ($i = 0; $best['loss'] > 25 && $i < 3; $i++) {
      $initial = [50, 20, 3750, 50, 100, 100];
      $result = $this->spsa($A, $a, $c, $initial, 1000);
      if ($result['loss'] < $best['loss']) {
        $best = $result;
      }
    }
    return $best;
  }

  private function solveNarrow($wide)
  {
    $A = $wide['loss'];
    $c = 2;
    $A1 = $A + 1;
    $a = [0.25 * $A1, 0.25 * $A1, $A1, 0.25 * $A1, 0.2 * $A1, 0.2 * $A1];
    return $this->spsa($A, $a, $c, $wide['values'], 500);
  }

  private function spsa($A, $a, $c, $values, $iters)
  {
    $alpha = 1;
    $gamma = 0.16666666666666666;

    $best = null;
    $bestLoss = INF;
    $n = count($values);

    for ($k = 0; $k < $iters; $k++) {
      $ck = $c / pow($k + 1, $gamma);
      $deltas = [];
      $highArgs = [];
      $lowArgs = [];

      for ($i = 0; $i < $n; $i++) {
        $deltas[$i] = mt_rand(0, 1) ? 1 : -1;
        $highArgs[$i] = $values[$i] + $ck * $deltas[$i];
        $lowArgs[$i] = $values[$i] - $ck * $deltas[$i];
      }

      $lossDiff = $this->loss($highArgs) - $this->loss($lowArgs);

      for ($i = 0; $i < $n; $i++) {
        $g = $lossDiff / (2 * $ck) * $deltas[$i];
        $ak = $a[$i] / pow($A + $k + 1, $alpha);
        $values[$i] = $this->fix($values[$i] - $ak * $g, $i);
      }

      $loss = $this->loss($values);
      if ($loss < $bestLoss) {
        $best = $values;
        $bestLoss = $loss;
      }
    }

    return ['values' => $best, 'loss' => $bestLoss];
  }

  private function fix($value, $idx)
  {
    $max = 100;
    if ($idx === 2) $max = 7500;
    elseif ($idx === 4 || $idx === 5) $max = 200;

    if ($idx === 3) {
      if ($value > $max) $value = fmod($value, $max);
      elseif ($value < 0) $value = $max + fmod($value, $max);
    } else {
      if ($value < 0) $value = 0;
      elseif ($value > $max) $value = $max;
    }

    return $value;
  }

  private function loss($filters)
  {
    $color = $this->reusedColor;
    $color->set(0, 0, 0);

    $color->invert($filters[0] / 100);
    $color->sepia($filters[1] / 100);
    $color->saturate($filters[2] / 100);
    $color->hueRotate($filters[3] * 3.6);
    $color->brightness($filters[4] / 100);
    $color->contrast($filters[5] / 100);

    return
      abs($color->r - $this->target->r) +
      abs($color->g - $this->target->g) +
      abs($color->b - $this->target->b);
  }

  private function css($filters)
  {
    return sprintf(
      'brightness(0) saturate(100%%) invert(%d%%) sepia(%d%%) saturate(%d%%) hue-rotate(%ddeg) brightness(%d%%) contrast(%d%%)',
      round($filters[0]),
      round($filters[1]),
      round($filters[2]),
      round($filters[3] * 3.6),
      round($filters[4]),
      round($filters[5])
    );
  }
}

new CarnavalSF_Customizer();
