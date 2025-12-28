/**
 * Add editor and footer container classes to apply style.css styles.
 */
(function () {
	function addFooterClasses() {
		const widgetEditor = document.getElementById('widgets-editor');
    if (!widgetEditor) {
      return;
    }

		const observer = new MutationObserver(function () {
      widgetAreas = document.querySelectorAll('[data-widget-area-id]');
      if (widgetAreas.length === 0) {
        return;
      }

		  widgetAreas.forEach(function (area) {
			  const id = area.dataset.widgetAreaId;
			  const layout = area.querySelector('.block-editor-block-list__layout');

        if (id.match(/^footer-column-\d+$/)) {
          layout.classList.add('footer-column');
          return;
        }

        if (id === 'footer-bottom') {
          layout.classList.add('footer-bottom');
          return;
        }
    });

      observer.disconnect();
		});
		observer.observe(widgetEditor, { childList: true, subtree: true });
	}

	function addEditorClasses() {
    const blockEditor = document.getElementById('editor');
    if (!blockEditor) {
      return;
    }

		const observer = new MutationObserver(function () {
      const iframe = document.querySelector('iframe');
      if (!iframe) {
        return;
      }

      const rootContainer = iframe.contentDocument.querySelector('.is-root-container');
      if (!rootContainer) {
        return;
      }

      rootContainer.classList.add('entry-content');
      observer.disconnect();
		});
		observer.observe(blockEditor, { childList: true, subtree: true });
	}

	document.addEventListener('DOMContentLoaded', addEditorClasses);
	document.addEventListener('DOMContentLoaded', addFooterClasses);
})();

