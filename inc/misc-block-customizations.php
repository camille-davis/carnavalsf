<?php
/**
 * Misc block customizations for Carnaval SF theme:
 * - Customize details block
 * - Add is-fullwidth toggle to group block
 * - Add is-fullwidth-image toggle to image block
 * - Add intermediate-width toggle to columns block
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
		add_filter( 'render_block_core/image', array( $this, 'image_block_fullwidth' ), 10, 2 );
		add_filter( 'render_block_core/columns', array( $this, 'columns_block_intermediate_width' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets() {
		$theme_version = carnavalsf_get_theme_version();
		$block_editor_deps = array(
			'wp-blocks',
			'wp-block-editor',
			'wp-components',
			'wp-compose',
			'wp-element',
			'wp-hooks',
		);

		$block_scripts = array(
			'carnavalsf-group-block-fullwidth'      => 'group-block-fullwidth.js',
			'carnavalsf-image-block-fullwidth'      => 'image-block-fullwidth.js',
			'carnavalsf-columns-block-intermediate-width' => 'columns-block-intermediate-width.js',
		);

		foreach ( $block_scripts as $handle => $file ) {
			wp_enqueue_script(
				$handle,
				get_template_directory_uri() . '/js/' . $file,
				$block_editor_deps,
				$theme_version,
				true
			);
		}

		wp_enqueue_script(
			'carnavalsf-dimensions-panel-title',
			get_template_directory_uri() . '/js/dimensions-panel-title.js',
			array( 'wp-i18n' ),
			$theme_version,
			true
		);

		wp_enqueue_script(
			'carnavalsf-add-editor-classes',
			get_template_directory_uri() . '/js/add-editor-classes.js',
			array(),
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
		return $this->add_block_class( $block_content, $block, 'wp-block-group', 'is-fullwidth', 'fullwidth' );
	}

	/**
	 * Add is-fullwidth-image class to Image block when fullwidth attribute is set.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 * @return string Modified block content.
	 */
	public function image_block_fullwidth( $block_content, $block ) {
		return $this->add_block_class( $block_content, $block, 'wp-block-image', 'is-fullwidth-image', 'fullwidth' );
	}

	/**
	 * Add has-intermediate-width class to Columns block when intermediateWidth attribute is set.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 * @return string Modified block content.
	 */
	public function columns_block_intermediate_width( $block_content, $block ) {
		return $this->add_block_class( $block_content, $block, 'wp-block-columns', 'has-intermediate-width', 'intermediateWidth' );
	}

	/**
	 * Add class to block content when attribute is set.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The block data.
	 * @param string $block_class The block class to match (e.g., 'wp-block-group').
	 * @param string $css_class The CSS class to add (e.g., 'is-fullwidth').
	 * @param string $attr_key The attribute key to check (e.g., 'fullwidth').
	 * @return string Modified block content.
	 */
	private function add_block_class( $block_content, $block, $block_class, $css_class, $attr_key ) {
		if ( ! isset( $block['attrs'][ $attr_key ] ) || ! $block['attrs'][ $attr_key ] || strpos( $block_content, $css_class ) !== false ) {
			return $block_content;
		}

		$block_content = preg_replace(
			'/(<[^>]*\bclass="[^"]*' . preg_quote( $block_class, '/' ) . '[^"]*)(")/',
			'$1 ' . $css_class . '$2',
			$block_content,
			1
		);

		return $block_content;
	}
}

new CarnavalSF_Blocks();
