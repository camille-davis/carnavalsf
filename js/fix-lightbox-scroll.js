/**
 * Fix jumpy scrolling when Gallery Block Lightbox is open.
 */

(function() {
	const overlay = document.querySelector('.wp-lightbox-overlay');
	if (overlay) {
		new MutationObserver(function() {
			document.body.classList.toggle('lightbox-open', overlay.classList.contains('active'));
		}).observe(overlay, {
			attributes: true,
			attributeFilter: ['class']
		});
	}
})();

