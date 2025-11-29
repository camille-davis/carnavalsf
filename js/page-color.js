(function ($) {
	'use strict';

	/**
	 * Update color swatches from localized data
	 */
	$(document).ready(function () {
		if (typeof carnavalsfPageColor !== 'undefined') {
			['color1', 'color2'].forEach((colorKey, index) => {
				$(
					'.carnavalsf-color-swatch input[value="color-' +
						(index + 1) +
						'"]'
				)
					.siblings('.swatch')
					.css('background-color', carnavalsfPageColor[colorKey]); // eslint-disable-line no-undef
			});
		}
	});

	/**
	 * Update editor iframe class when page color changes
	 */
	function updateEditorClass() {
		const colorInput = document.querySelector(
			'input[name="carnavalsf_page_color"]:checked'
		);
		const pageColorClass = colorInput
			? 'page-' + colorInput.value
			: 'page-color-1';

		// Find editor iframe - WordPress block editor uses iframe with name 'editor-canvas'
		const editorIframe =
			document.querySelector('iframe[name="editor-canvas"]') ||
			document.querySelector('iframe.editor-canvas');

		if (editorIframe) {
			try {
				const editorWrapper =
					editorIframe.contentDocument?.querySelector(
						'.editor-styles-wrapper'
					);
				if (editorWrapper) {
					editorWrapper.classList.remove(
						'page-color-1',
						'page-color-2'
					);
					editorWrapper.classList.add(pageColorClass);
				}
			} catch (e) {
				// Skip if we can't access iframe
			}
		}
	}

	// Listen to meta box changes
	document.addEventListener('change', function (e) {
		if (e.target.name === 'carnavalsf_page_color') {
			updateEditorClass();
		}
	});

	// Initial update
	setTimeout(updateEditorClass, 500);
})(jQuery);
