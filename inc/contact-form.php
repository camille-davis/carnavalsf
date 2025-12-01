<?php
/**
 * Contact Form
 *
 * @package CarnavalSF
 */

class CarnavalSF_Contact_Form {
	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'wp_ajax_carnavalsf_contact_form', array( $this, 'handle_submission' ) );
		add_action( 'wp_ajax_nopriv_carnavalsf_contact_form', array( $this, 'handle_submission' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

  /**
   * Registers the contact form block.
   */
	public function register_block() {

    // Loads index.js in block editor.
		wp_register_script(
      'carnavalsf-contact-form-block-editor',
      get_template_directory_uri() . '/blocks/contact-form/index.js',
      array( 'wp-blocks', 'wp-block-editor', 'wp-server-side-render', 'wp-element', 'wp-components', 'wp-i18n' ),
      wp_get_theme()->get( 'Version' ),
      true
    );

    // Reads block.json and registers the block.
		register_block_type(
      get_template_directory() . '/blocks/contact-form',
      array( 'editor_script' => 'carnavalsf-contact-form-block-editor' )
    );
	}

  /**
   * Handles the contact form submission on the backend.
   */
	public function handle_submission() {
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ), 'carnavalsf_contact_form_nonce' ) || ! empty( $_POST['website'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'carnavalsf' ) ) );
		}

		$name = sanitize_text_field( $_POST['name'] ?? '' );
		$email = sanitize_email( $_POST['email'] ?? '' );
		$message = sanitize_textarea_field( $_POST['message'] ?? '' );

		// Check for URLs in name or message fields.
		if ( ( ! empty( $name ) && ( strpos( $name, 'http://' ) !== false || strpos( $name, 'https://' ) !== false ) ) ||
			( ! empty( $message ) && ( strpos( $message, 'http://' ) !== false || strpos( $message, 'https://' ) !== false ) ) ) {
			wp_send_json_error( array( 'message' => __( 'Sorry, links are not allowed. Please remove them and resend your message.', 'carnavalsf' ) ) );
		}

		if ( empty( $name ) || empty( $email ) || ! is_email( $email ) || empty( $message ) ) {
			wp_send_json_error( array( 'message' => __( 'Please fill in all required fields with valid information.', 'carnavalsf' ) ) );
		}

		// Use recipient email from block if provided and valid, otherwise use admin email.
		$recipient_email = ! empty( $_POST['recipient_email'] ) ? sanitize_email( $_POST['recipient_email'] ) : '';
		$to = ! empty( $recipient_email ) && is_email( $recipient_email ) ? $recipient_email : get_option( 'admin_email' );
		$site_name = get_bloginfo( 'name' );
		$headers = array( 'From: ' . $name . ' <' . $email . '>', 'Reply-To: ' . $email, 'Content-Type: text/plain; charset=UTF-8' );

		if ( ! wp_mail( $to, sprintf( __( 'Contact Form Submission from %s', 'carnavalsf' ), $site_name ), sprintf( "%s: %s\n\n%s: %s\n\n%s:\n%s", __( 'Name', 'carnavalsf' ), $name, __( 'Email', 'carnavalsf' ), $email, __( 'Message', 'carnavalsf' ), $message ), $headers ) ) {
			wp_send_json_error( array( 'message' => __( 'Sorry, there was an error sending your message. Please try again later or email us directly.', 'carnavalsf' ) ) );
		}

		wp_send_json_success( array( 'message' => __( 'Thank you for contacting us! We will get back to you soon.', 'carnavalsf' ) ) );
	}

	/**
	 * Adds contact form js functionality to frontend.
	 */
	public function enqueue_scripts() {

		// Check if block exists in post content.
		$has_block = ( $post = get_post() ) && has_block( 'carnavalsf/contact-form', $post );

		// If not found in post, check all widget content.
		if ( ! $has_block ) {
			$widget_content = '';
			$widgets = get_option( 'widget_block', array() );
			foreach ( $widgets as $widget ) {
				if ( ! empty( $widget['content'] ) ) {
					$widget_content .= $widget['content'];
				}
			}
			$has_block = ! empty( $widget_content ) && has_block( 'carnavalsf/contact-form', $widget_content );
		}

		if ( ! $has_block ) return;

		wp_enqueue_script( 'carnavalsf-contact-form', get_template_directory_uri() . '/js/contact-form.js', array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );
		wp_localize_script( 'carnavalsf-contact-form', 'carnavalsfContactForm', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'carnavalsf_contact_form_nonce' ) ) );
	}
}

new CarnavalSF_Contact_Form();
