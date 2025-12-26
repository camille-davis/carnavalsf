<?php
/**
 * Customizer functionality for Carnaval SF theme.
 *
 * @package CarnavalSF
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load color utility functions and classes.
require_once get_template_directory() . '/inc/color-converter.php';

/**
 * Customizer class.
 */
class CarnavalSF_Customizer {

	/**
	 * Default fonts URL.
	 *
	 * @var string
	 */
	private const DEFAULT_FONTS_URL = 'https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&family=Saira+Condensed:wght@400;800&display=block';

	/**
	 * Allowed font hosts.
	 *
	 * @var array
	 */
	private const ALLOWED_FONT_HOSTS = array( 'fonts.googleapis.com', 'fonts.gstatic.com' );

	/**
	 * Default color values.
	 *
	 * @var array
	 */
	public const DEFAULT_COLORS = array(
		'accent_color_1'   => '#FFA843',
		'accent_color_2'   => '#9C286E',
		'accent_color_3'   => '#05DFD7',
		'dark_text_color'  => '#383838',
		'light_text_color' => '#FFFFFF',
	);

	/**
	 * Default typography values.
	 *
	 * @var array
	 */
	private const DEFAULT_TYPOGRAPHY = array(
		'body_font'      => 'Quicksand',
		'accent_font'    => 'Saira Condensed',
		'body_font_size' => '1rem',
		'h1_font_size'   => '6.25rem',
		'h2_font_size'   => '4.25rem',
		'h3_font_size'   => '3rem',
		'h4_font_size'   => '2rem',
		'h5_font_size'   => '1.25rem',
		'h6_font_size'   => '1rem',
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_controls' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'add_reset_buttons' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'add_customizer_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_inline_css' ), 20 );
		add_action( 'after_setup_theme', array( $this, 'register_editor_color_palette' ) );
		add_filter( 'block_editor_settings_all', array( $this, 'add_editor_inline_css' ), 10, 1 );
	}

	/**
	 * Register customizer controls and settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_controls( $wp_customize ) {
		$this->register_color_controls( $wp_customize );
		$this->register_typography_controls( $wp_customize );
	}

	/**
	 * Register color controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	private function register_color_controls( $wp_customize ) {

		// Colors Section.
		$wp_customize->add_section(
			'carnavalsf_colors',
			array(
				'title'    => __( 'Theme Colors', 'carnavalsf' ),
				'priority' => 30,
			)
		);

		// Color settings.
		$colors = array(
			'accent_color_1'   => __( 'Accent Color 1', 'carnavalsf' ),
			'accent_color_2'   => __( 'Accent Color 2', 'carnavalsf' ),
			'accent_color_3'   => __( 'Accent Color 3', 'carnavalsf' ),
			'dark_text_color'  => __( 'Dark Text Color', 'carnavalsf' ),
			'light_text_color' => __( 'Light Text Color', 'carnavalsf' ),
		);

		foreach ( $colors as $setting_id => $label ) {
			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => self::DEFAULT_COLORS[ $setting_id ],
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'   => $label,
						'section' => 'carnavalsf_colors',
					)
				)
			);
		}
	}

	/**
	 * Register typography controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	private function register_typography_controls( $wp_customize ) {

		// Typography Section.
		$wp_customize->add_section(
			'carnavalsf_typography',
			array(
				'title'    => __( 'Typography', 'carnavalsf' ),
				'priority' => 35,
			)
		);

		// Fonts URL.
		$wp_customize->add_setting(
			'fonts_url',
			array(
				'default'           => self::DEFAULT_FONTS_URL,
				'sanitize_callback' => array( $this, 'sanitize_font_url' ),
			)
		);

		$wp_customize->add_control(
			'fonts_url',
			array(
				'label'   => __( 'Fonts URL', 'carnavalsf' ),
				'section' => 'carnavalsf_typography',
				'type'    => 'text',
			)
		);

		// Typography text controls.
		$typography_controls = array(
			'body_font'      => __( 'Body Font', 'carnavalsf' ),
			'accent_font'    => __( 'Accent Font', 'carnavalsf' ),
			'body_font_size' => __( 'Body Font Size', 'carnavalsf' ),
		);

		foreach ( $typography_controls as $setting_id => $label ) {
			$this->register_text_control( $wp_customize, $setting_id, $label, 'carnavalsf_typography' );
		}

		// Heading Font Sizes.
		$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		foreach ( $headings as $heading ) {
			$setting_id = "{$heading}_font_size";
			$label      = sprintf(
				// translators: %s is the heading name.
				__( '%s Size', 'carnavalsf' ),
				ucfirst( $heading )
			);
			$this->register_text_control( $wp_customize, $setting_id, $label, 'carnavalsf_typography' );
		}
	}

	/**
	 * Register a text control in the customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @param string               $setting_id   Setting ID.
	 * @param string               $label        Control label.
	 * @param string               $section      Section ID.
	 */
	private function register_text_control( $wp_customize, $setting_id, $label, $section ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => self::DEFAULT_TYPOGRAPHY[ $setting_id ] ?? '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $label,
				'section' => $section,
				'type'    => 'text',
			)
		);
	}

	/**
	 * Sanitize font URL to only allow trusted sources.
	 *
	 * @param string $url The font URL to sanitize.
	 * @return string Sanitized URL or default if invalid.
	 */
	public function sanitize_font_url( $url ) {
		$url = esc_url_raw( $url );

		// If empty, return default.
		if ( empty( $url ) ) {
			return self::DEFAULT_FONTS_URL;
		}

		$parsed = wp_parse_url( $url );

		// If URL cannot be parsed or has no host, return default.
		if ( ! $parsed || empty( $parsed['host'] ) ) {
			return self::DEFAULT_FONTS_URL;
		}

		$host = strtolower( $parsed['host'] );

		// Allow same domain or subdomain.
		if ( isset( $_SERVER['HTTP_HOST'] ) && strpos( $host, strtolower( $_SERVER['HTTP_HOST'] ) ) !== false ) {
			return $url;
		}

		// Check against allowed hosts.
		foreach ( self::ALLOWED_FONT_HOSTS as $allowed ) {
			if ( $host === $allowed || strpos( $host, '.' . $allowed ) !== false ) {
				return $url;
			}
		}

		// If not allowed, return default.
		return self::DEFAULT_FONTS_URL;
	}

	/**
	 * Add reset buttons to customizer controls.
	 */
	public function add_reset_buttons() {
		global $wp_customize;

		// Get typography setting defaults and build inline script in a single loop.
		$inline_script = '';
		$controls      = $wp_customize->controls();
		foreach ( $controls as $control ) {
			if ( 'carnavalsf_typography' !== $control->section ) {
				continue;
			}
			$setting = $wp_customize->get_setting( $control->id );
			if ( $setting ) {
				$inline_script .= sprintf(
					'wp.customize("%s", function(setting) { setting.default = %s; });',
					$control->id,
					wp_json_encode( $setting->default )
				);
			}
		}
		wp_add_inline_script( 'customize-controls', $inline_script );

		// Add 'Reset' buttons.
		$theme_version = carnavalsf_get_theme_version();
		wp_enqueue_script(
			'carnavalsf-customizer',
			get_template_directory_uri() . '/js/customizer.js',
			array( 'jquery', 'customize-controls' ),
			$theme_version,
			true
		);

		wp_localize_script(
			'carnavalsf-customizer',
			'carnavalsfCustomizer',
			array(
				'resetText' => __( 'Reset', 'carnavalsf' ),
			)
		);
	}

	/**
	 * Add customizer styles.
	 */
	public function add_customizer_style() {
		$theme_version = carnavalsf_get_theme_version();
		wp_enqueue_style(
			'carnavalsf-customizer',
			get_template_directory_uri() . '/css/customizer.css',
			array(),
			$theme_version
		);
	}

	/**
	 * Add CSS variables to editor canvas only (not sidebar).
	 *
	 * @param array $editor_settings Editor settings array.
	 * @return array Modified editor settings.
	 */
	public function add_editor_inline_css( $editor_settings ) {

		// Get the CSS variables.
		$inline_css = self::get_inline_css();

		if ( empty( $inline_css ) ) {
			return $editor_settings;
		}

		// Ensure styles array exists.
		if ( ! isset( $editor_settings['styles'] ) ) {
			$editor_settings['styles'] = array();
		}

		// Prepend CSS variables so they're available before other styles.
		// Must set isGlobalStyles to false or it will be stripped out.
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

	/**
	 * Register editor color palette with accent colors from customizer.
	 */
	public function register_editor_color_palette() {
		$color_map = array(
			'accent_color_1'   => array( 'name' => __( 'Accent Color 1', 'carnavalsf' ), 'slug' => 'accent-color-1' ),
			'accent_color_2'   => array( 'name' => __( 'Accent Color 2', 'carnavalsf' ), 'slug' => 'accent-color-2' ),
			'accent_color_3'   => array( 'name' => __( 'Accent Color 3', 'carnavalsf' ), 'slug' => 'accent-color-3' ),
			'dark_text_color'  => array( 'name' => __( 'Dark Text Color', 'carnavalsf' ), 'slug' => 'dark-text-color' ),
			'light_text_color' => array( 'name' => __( 'Light Text Color', 'carnavalsf' ), 'slug' => 'light-text-color' ),
		);

		$editor_color_palette = array();
		foreach ( $color_map as $setting_id => $args ) {
			$editor_color_palette[] = array(
				'name'  => $args['name'],
				'slug'  => $args['slug'],
				'color' => get_theme_mod( $setting_id, self::DEFAULT_COLORS[ $setting_id ] ),
			);
		}

		add_theme_support( 'editor-color-palette', $editor_color_palette );
	}

	/**
	 * Get inline CSS with CSS variables.
	 *
	 * @return string CSS string with variables.
	 */
	public static function get_inline_css() {
		$css = '';

		// Output font import.
		$fonts_url = get_theme_mod( 'fonts_url', self::DEFAULT_FONTS_URL );
		if ( $fonts_url ) {
			$css .= '@import url("' . esc_url_raw( $fonts_url ) . '");';
		}

		// Cache theme mod values to avoid repeated calls.
		$accent_color_1   = get_theme_mod( 'accent_color_1', self::DEFAULT_COLORS['accent_color_1'] );
		$accent_color_2   = get_theme_mod( 'accent_color_2', self::DEFAULT_COLORS['accent_color_2'] );
		$accent_color_3   = get_theme_mod( 'accent_color_3', self::DEFAULT_COLORS['accent_color_3'] );
		$dark_text_color  = get_theme_mod( 'dark_text_color', self::DEFAULT_COLORS['dark_text_color'] );
		$light_text_color = get_theme_mod( 'light_text_color', self::DEFAULT_COLORS['light_text_color'] );

		// Output custom CSS variables.
		$custom_css_variables = array(
			'accent-color-1'        => $accent_color_1,
			'accent-color-1-filter' => CarnavalSF_Color_Converter::hex_to_css_filter( $accent_color_1 ),
			'accent-color-1-rgb'    => implode( ',', CarnavalSF_Color_Converter::hex_to_rgb( $accent_color_1 ) ),
			'accent-color-2'        => $accent_color_2,
			'accent-color-2-filter' => CarnavalSF_Color_Converter::hex_to_css_filter( $accent_color_2 ),
			'accent-color-2-rgb'    => implode( ',', CarnavalSF_Color_Converter::hex_to_rgb( $accent_color_2 ) ),
			'accent-color-3'        => $accent_color_3,
			'accent-color-3-filter' => CarnavalSF_Color_Converter::hex_to_css_filter( $accent_color_3 ),
			'accent-color-3-rgb'    => implode( ',', CarnavalSF_Color_Converter::hex_to_rgb( $accent_color_3 ) ),
			'dark-text'             => $dark_text_color,
			'dark-text-filter'      => CarnavalSF_Color_Converter::hex_to_css_filter( $dark_text_color ),
			'dark-text-rgb'         => implode( ',', CarnavalSF_Color_Converter::hex_to_rgb( $dark_text_color ) ),
			'light-text'            => $light_text_color,
			'light-text-filter'     => CarnavalSF_Color_Converter::hex_to_css_filter( $light_text_color ),
			'light-text-rgb'        => implode( ',', CarnavalSF_Color_Converter::hex_to_rgb( $light_text_color ) ),
			'body-font'      => get_theme_mod( 'body_font', self::DEFAULT_TYPOGRAPHY['body_font'] ) . ', sans-serif',
			'accent-font'    => get_theme_mod( 'accent_font', self::DEFAULT_TYPOGRAPHY['accent_font'] ) . ', sans-serif',
			'body-font-size' => get_theme_mod( 'body_font_size', self::DEFAULT_TYPOGRAPHY['body_font_size'] ),
			'h1-font-size'   => get_theme_mod( 'h1_font_size', self::DEFAULT_TYPOGRAPHY['h1_font_size'] ),
			'h2-font-size'   => get_theme_mod( 'h2_font_size', self::DEFAULT_TYPOGRAPHY['h2_font_size'] ),
			'h3-font-size'   => get_theme_mod( 'h3_font_size', self::DEFAULT_TYPOGRAPHY['h3_font_size'] ),
			'h4-font-size'   => get_theme_mod( 'h4_font_size', self::DEFAULT_TYPOGRAPHY['h4_font_size'] ),
			'h5-font-size'   => get_theme_mod( 'h5_font_size', self::DEFAULT_TYPOGRAPHY['h5_font_size'] ),
			'h6-font-size'   => get_theme_mod( 'h6_font_size', self::DEFAULT_TYPOGRAPHY['h6_font_size'] ),
		);

		$css .= ':root {';
		foreach ( $custom_css_variables as $name => $value ) {
			$css .= "--{$name}: {$value};";
		}
		$css .= '}';

		return $css;
	}

	/**
	 * Attach inline CSS to the main theme stylesheet on the frontend.
	 */
	public function enqueue_frontend_inline_css() {
		$inline_css = self::get_inline_css();

		if ( empty( $inline_css ) ) {
			return;
		}

		// Attach the CSS variables to the main theme stylesheet.
		wp_add_inline_style( 'carnavalsf-style', $inline_css );
	}
}

new CarnavalSF_Customizer();
