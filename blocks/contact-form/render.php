<?php
/**
 * Contact Form Block Render Template
 *
 * @package CarnavalSF
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id = 'contact-form-' . wp_unique_id();

// Check if block is being rendered by index.js.
// If so we are in the editor and will disable form submission.
$is_editor = ( defined( 'REST_REQUEST' ) && REST_REQUEST );

// Get email from block attributes, fallback to empty string.
$recipient_email = ! empty( $attributes['email'] ) ? sanitize_email( $attributes['email'] ) : '';
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="carnavalsf-contact-form-wrapper">
		<form class="carnavalsf-contact-form" method="post"<?php echo ! empty( $recipient_email ) ? ' data-recipient-email="' . esc_attr( $recipient_email ) . '"' : ''; ?>>
			<div class="form-group">
				<label for="<?php echo esc_attr( $id ); ?>-name"><?php esc_html_e( 'Name', 'carnavalsf' ); ?> </label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-name" name="name" required>
			</div>
			<div class="form-group">
				<label for="<?php echo esc_attr( $id ); ?>-email"><?php esc_html_e( 'Email', 'carnavalsf' ); ?> </label>
				<input type="email" id="<?php echo esc_attr( $id ); ?>-email" name="email" required>
			</div>
			<div class="form-group">
				<label for="<?php echo esc_attr( $id ); ?>-message"><?php esc_html_e( 'Message', 'carnavalsf' ); ?> </label>
				<textarea id="<?php echo esc_attr( $id ); ?>-message" name="message" rows="5" required></textarea>
			</div>
			<div style="position: absolute; left: -9999px; opacity: 0;">
				<input type="text" id="<?php echo esc_attr( $id ); ?>-website" name="website" tabindex="-1" autocomplete="off">
			</div>
			<div class="form-group">
				<button type="submit" class="submit-button"<?php echo $is_editor ? ' disabled' : ''; ?>><?php esc_html_e( 'Send Message', 'carnavalsf' ); ?></button>
			</div>
			<p class="form-message" aria-live="assertive"></p>
		</form>
	</div>
</div>

