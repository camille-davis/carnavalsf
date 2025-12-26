<?php
/**
 * Misc block customizations for Carnaval SF theme:
 * - Customize details block
 * - Add is-fullwidth class to group block
 * - Change 'Dimensions' panel title to 'Spacing'
 *
 * @package CarnavalSF
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Customizations Class
 */
class CarnavalSF_Blocks {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'render_block', array( $this, 'customize_details_block' ), 10, 2 );
		add_filter( 'render_block_core/group', array( $this, 'group_block_fullwidth' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets() {
		$theme_version = wp_get_theme()->get( 'Version' );

		// Group block fullwidth functionality.
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
			$theme_version,
			true
		);

		// Dimensions panel title customization.
		wp_enqueue_script(
			'carnavalsf-dimensions-panel-title',
			get_template_directory_uri() . '/js/dimensions-panel-title.js',
			array( 'wp-i18n' ),
			$theme_version,
			true
		);
	}

	/**
	 * Customize Details Gutenberg block.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 * @return string Modified block content.
	 */
	public function customize_details_block( $block_content, $block ) {
		if ( 'core/details' === $block['blockName'] ) {
			$block_content = preg_replace(
				array( '/<summary>/', '/<\/summary>/', '/<\/details>/' ),
				array( '<summary><h3>', '</h3></summary><div class="details-content">', '</div></details>' ),
				$block_content
			);
		}

		return $block_content;
	}

	/**
	 * Add is-fullwidth class to Group block when fullwidth attribute is set.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 * @return string Modified block content.
	 */
	public function group_block_fullwidth( $block_content, $block ) {
		if ( empty( $block['attrs']['fullwidth'] ) ) {
			return $block_content;
		}

		// Check if already has the class.
		if ( strpos( $block_content, 'is-fullwidth' ) !== false ) {
			return $block_content;
		}

		// Add class to the first wp-block-group class attribute.
		$block_content = preg_replace(
			'/(<[^>]*\bclass="[^"]*wp-block-group[^"]*)(")/',
			'$1 is-fullwidth$2',
			$block_content,
			1
		);

		return $block_content;
	}
}

new CarnavalSF_Blocks();
