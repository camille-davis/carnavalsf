(function() {
  'use strict';

  function updateEditorClass() {
    const colorInput = document.querySelector('input[name="carnavalsf_page_color"]:checked');
    const pageColorClass = colorInput ? 'page-' + colorInput.value : 'page-color-1';

    // Find editor iframe and update class
    const iframes = document.querySelectorAll('iframe');
    for (const iframe of iframes) {
      try {
        const editorWrapper = iframe.contentDocument?.querySelector('.editor-styles-wrapper');
        if (editorWrapper) {
          editorWrapper.classList.remove('page-color-1', 'page-color-2');
          editorWrapper.classList.add(pageColorClass);
        }
      } catch (e) {
        // Skip iframes we can't access
      }
    }
  }

  // Listen to meta box changes
  document.addEventListener('change', function(e) {
    if (e.target.name === 'carnavalsf_page_color') {
      updateEditorClass();
    }
  });

  // Initial update
  setTimeout(updateEditorClass, 500);
})();
