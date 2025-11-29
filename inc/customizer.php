<?php
/**
 * Customizer functionality for Carnaval SF theme.
 *
 * @package CarnavalSF
 */

// Load color utility functions and classes.
require_once get_template_directory() . '/inc/color-converter.php';

/**
 * Customizer class.
 */
class CarnavalSF_Customizer {

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
			'accent_color_1'   => array(
				'default' => '#FFA843',
				'label'   => __( 'Accent Color 1', 'carnavalsf' ),
			),
			'accent_color_2'   => array(
				'default' => '#9C286E',
				'label'   => __( 'Accent Color 2', 'carnavalsf' ),
			),
			'accent_color_3'   => array(
				'default' => '#05DFD7',
				'label'   => __( 'Accent Color 3', 'carnavalsf' ),
			),
			'dark_text_color'  => array(
				'default' => '#383838',
				'label'   => __( 'Dark Text Color', 'carnavalsf' ),
			),
			'light_text_color' => array(
				'default' => '#FFFFFF',
				'label'   => __( 'Light Text Color', 'carnavalsf' ),
			),
		);

		foreach ( $colors as $setting_id => $args ) {
			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => $args['default'],
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'   => $args['label'],
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
				'default'           => 'https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&family=Saira+Condensed:wght@400;800&display=block',
				'sanitize_callback' => 'esc_url_raw',
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

		// Body Font.
		$wp_customize->add_setting(
			'body_font',
			array(
				'default'           => 'Quicksand',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'body_font',
			array(
				'label'   => __( 'Body Font', 'carnavalsf' ),
				'section' => 'carnavalsf_typography',
				'type'    => 'text',
			)
		);

		// Accent Font.
		$wp_customize->add_setting(
			'accent_font',
			array(
				'default'           => 'Saira Condensed',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'accent_font',
			array(
				'label'   => __( 'Accent Font', 'carnavalsf' ),
				'section' => 'carnavalsf_typography',
				'type'    => 'text',
			)
		);

		// Body Font Size.
		$wp_customize->add_setting(
			'body_font_size',
			array(
				'default'           => '1rem',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'body_font_size',
			array(
				'label'   => __( 'Body Font Size', 'carnavalsf' ),
				'section' => 'carnavalsf_typography',
				'type'    => 'text',
			)
		);

		// Heading Font Sizes.
		$headings = array(
			'h1' => '6.25rem',
			'h2' => '4.25rem',
			'h3' => '2.5rem',
			'h4' => '1.75rem',
			'h5' => '1.25rem',
			'h6' => '1rem',
		);

		foreach ( $headings as $heading => $default ) {
			$wp_customize->add_setting(
				"{$heading}_font_size",
				array(
					'default'           => $default,
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control(
				"{$heading}_font_size",
				array(
					'label'   => sprintf(
						// translators: %s is the heading name.
						__( '%s Size', 'carnavalsf' ),
						ucfirst( $heading )
					),
					'section' => 'carnavalsf_typography',
					'type'    => 'text',
				)
			);
		}
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
		wp_enqueue_script(
			'carnavalsf-customizer',
			get_template_directory_uri() . '/js/customizer.js',
			array( 'jquery', 'customize-controls' ),
			wp_get_theme()->get( 'Version' ),
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
		wp_enqueue_style(
			'carnavalsf-customizer',
			get_template_directory_uri() . '/css/customizer.css',
			array(),
			wp_get_theme()->get( 'Version' )
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
		$fonts_url = get_theme_mod( 'fonts_url' );
		if ( $fonts_url ) {
			$css .= '@import url("' . esc_url_raw( $fonts_url ) . '");';
		}

		// Cache theme mod values to avoid repeated calls.
		$accent_color_1   = get_theme_mod( 'accent_color_1', '#FFA843' );
		$accent_color_2   = get_theme_mod( 'accent_color_2', '#9C286E' );
		$accent_color_3   = get_theme_mod( 'accent_color_3', '#05DFD7' );
		$dark_text_color  = get_theme_mod( 'dark_text_color', '#383838' );
		$light_text_color = get_theme_mod( 'light_text_color', '#FFFFFF' );

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
			'body-font'             => get_theme_mod( 'body_font', 'Quicksand' ) . ', sans-serif',
			'accent-font'           => get_theme_mod( 'accent_font', 'Saira Condensed' ) . ', sans-serif',
			'body-font-size'        => get_theme_mod( 'body_font_size', '1rem' ),
			'h1-font-size'          => get_theme_mod( 'h1_font_size', '6.25rem' ),
			'h2-font-size'          => get_theme_mod( 'h2_font_size', '4.25rem' ),
			'h3-font-size'          => get_theme_mod( 'h3_font_size', '2.5rem' ),
			'h4-font-size'          => get_theme_mod( 'h4_font_size', '1.75rem' ),
			'h5-font-size'          => get_theme_mod( 'h5_font_size', '1.25rem' ),
			'h6-font-size'          => get_theme_mod( 'h6_font_size', '1rem' ),
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
