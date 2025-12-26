<?php
/**
 * Page Color Meta Box functionality for Carnaval SF theme
 *
 * @package CarnavalSF
 */

/**
 * Page Color Meta Box Class
 */
class CarnavalSF_Page_Color {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_page_color_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_page_color' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Add page color meta box to page edit screen.
	 *
	 * @return void
	 */
	public function add_page_color_meta_box() {
		add_meta_box(
			'carnavalsf_page_color',
			__( 'Page Color', 'carnavalsf' ),
			array( $this, 'render_page_color_meta_box' ),
			'page',
			'side',
			'default'
		);
	}

	/**
	 * Render the page color meta box
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_page_color_meta_box( $post ) {
		wp_nonce_field( 'carnavalsf_page_color_nonce', 'carnavalsf_page_color_nonce' );

		$selected_color = get_post_meta( $post->ID, '_carnavalsf_page_color', true );
		if ( empty( $selected_color ) ) {
			$selected_color = 'color-1';
		}

		// Get colors from customizer.
		$color_1 = get_theme_mod( 'accent_color_1', '#FFA843' );
		$color_2 = get_theme_mod( 'accent_color_2', '#9C286E' );
		?>
		<div class="carnavalsf-page-color-selector">
			<p><?php esc_html_e( 'Select a page color:', 'carnavalsf' ); ?></p>
			<div class="carnavalsf-color-swatches">
				<label class="carnavalsf-color-swatch">
					<input type="radio" name="carnavalsf_page_color" value="color-1" <?php checked( $selected_color, 'color-1' ); ?>>
					<span class="swatch" style="background-color: <?php echo esc_attr( $color_1 ); ?>;"></span>
					<span class="swatch-label"><?php echo esc_html( $color_1 ); ?></span>
				</label>
				<label class="carnavalsf-color-swatch">
					<input type="radio" name="carnavalsf_page_color" value="color-2" <?php checked( $selected_color, 'color-2' ); ?>>
					<span class="swatch" style="background-color: <?php echo esc_attr( $color_2 ); ?>;"></span>
					<span class="swatch-label"><?php echo esc_html( $color_2 ); ?></span>
				</label>
			</div>
			<p class="description"><?php esc_html_e( 'This color will be applied to the page background.', 'carnavalsf' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Save page color meta box data.
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	public function save_page_color( $post_id ) {

		// Check nonce.
		if ( ! isset( $_POST['carnavalsf_page_color_nonce'] ) ||
			! wp_verify_nonce( $_POST['carnavalsf_page_color_nonce'], 'carnavalsf_page_color_nonce' ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check post type.
		if ( get_post_type( $post_id ) !== 'page' ) {
			return;
		}

		// Save the value.
		$color = 'color-1'; // Default.
		if ( isset( $_POST['carnavalsf_page_color'] ) ) {
			$submitted_color = sanitize_text_field( wp_unslash( $_POST['carnavalsf_page_color'] ) );
			if ( in_array( $submitted_color, array( 'color-1', 'color-2' ), true ) ) {
				$color = $submitted_color;
			}
		}
		update_post_meta( $post_id, '_carnavalsf_page_color', $color );
	}

	/**
	 * Enqueue admin assets for page color meta box.
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook ) {

		// Only load on page edit screens.
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}

		global $post_type;
		if ( 'page' !== $post_type ) {
			return;
		}

		$theme_version = wp_get_theme()->get( 'Version' );

		// Enqueue admin CSS.
		wp_enqueue_style(
			'carnavalsf-page-color-admin',
			get_template_directory_uri() . '/css/page-color-admin.css',
			array(),
			$theme_version
		);

		// Enqueue page color JavaScript (handles both admin swatches and editor updates).
		wp_enqueue_script(
			'carnavalsf-page-color',
			get_template_directory_uri() . '/js/page-color.js',
			array( 'jquery' ),
			$theme_version,
			true
		);

		// Localize script with customizer colors.
		wp_localize_script(
			'carnavalsf-page-color',
			'carnavalsfPageColor',
			array(
				'color1' => get_theme_mod( 'accent_color_1', '#FFA843' ),
				'color2' => get_theme_mod( 'accent_color_2', '#9C286E' ),
			)
		);
	}
}

new CarnavalSF_Page_Color();

