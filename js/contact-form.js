/**
 * Contact Form AJAX Handler
 */

(function ($) {
	$(document).on('submit', '.carnavalsf-contact-form', function (e) {
		e.preventDefault();

		const $form = $(this);
		const $msg = $form.closest('.carnavalsf-contact-form-wrapper').find('.form-message');
		const $btn = $form.find('button[type="submit"]');
		const btnText = $btn.text();
		const recipientEmail = $form.data('recipient-email') || '';

    $msg.hide();
		$btn.prop('disabled', true).text('Sending...');

		// Send form data to contact-form.php handle_submission function.
		$.ajax({
			url: carnavalsfContactForm.ajaxUrl,
			type: 'POST',
			data: {
				action: 'carnavalsf_contact_form',
				nonce: carnavalsfContactForm.nonce,
				name: $form.find('input[name="name"]').val(),
				email: $form.find('input[name="email"]').val(),
				message: $form.find('textarea[name="message"]').val(),
				website: $form.find('input[name="website"]').val() || '', // Honeypot field (hidden).
				recipient_email: recipientEmail,
			},
			success: (r) => {
				$msg.text(r.data.message)
					.css('opacity', 0)
					.slideDown(200)
					.animate(
						{ opacity: 1 },
						{ queue: false, duration: 200 }
					);

				// Reset form fields on successful submission.
				if (r.success) $form[0].reset();
			},
			error: () => {

				// Show generic error message if AJAX request fails.
				$msg.text('An error occurred. Please try again later.').fadeIn();
			},
			complete: () => {

				// Re-enable submit button and restore original text.
				$btn.prop('disabled', false).text(btnText);
			},
		});
	});
})(jQuery);

