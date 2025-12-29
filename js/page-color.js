(function ($) {

	/**
	 * Updates page color preview in editor when user selects a new color.
	 */
	function updateColorSwatches() {
		document.querySelectorAll('input[name="carnavalsf_page_color"]').forEach((input) => {
			input.addEventListener('click', function () {
      	const iframe = document.querySelector('iframe');
      	const editorStylesWrapper = iframe.contentDocument.querySelector('.editor-styles-wrapper');

				// Remove any class starting with 'page-color-'.
				editorStylesWrapper.classList.forEach(function (className) {
					if (className.startsWith('page-color-')) {
						editorStylesWrapper.classList.remove(className);
					}
				});

				// Add the selected color class.
				editorStylesWrapper.classList.add('page-color-' + this.value);
			});
		});
	}

	/*
	 * Sets initial page color class on iframe load.
	 */
	function addPageColorClasses() {
    const blockEditor = document.getElementById('editor');
    if (!blockEditor) {
      return;
    }

		// Get initial color class from input.
		const colorInput = document.querySelector(
			'input[name="carnavalsf_page_color"]:checked'
		);
		const pageColorClass = colorInput ? 'page-color-' + colorInput.value : 'page-color-1';

		// Wait for iframe to load then set the class.
		const observer = new MutationObserver(function () {
      const iframe = document.querySelector('iframe');
      if (!iframe) {
        return;
      }

      const editorStylesWrapper = iframe.contentDocument.querySelector('.editor-styles-wrapper');
      if (!editorStylesWrapper) {
        return;
      }

      editorStylesWrapper.classList.add(pageColorClass);
      observer.disconnect();
		});
		observer.observe(blockEditor, { childList: true, subtree: true });
	}

	document.addEventListener('DOMContentLoaded', addPageColorClasses);
	document.addEventListener('DOMContentLoaded', updateColorSwatches);
})(jQuery);
