(function () {
	const { addFilter } = wp.hooks;

	// Set default linkTo attribute for gallery block to 'lightbox' (enlarge on click)
	addFilter(
		'blocks.registerBlockType',
		'carnavalsf/gallery-default-link',
		(settings, name) => {
			if (name !== 'core/gallery') {
				return settings;
			}

			return Object.assign({}, settings, {
				attributes: Object.assign({}, settings.attributes, {
					linkTo: {
						type: 'string',
						default: 'lightbox', // 'lightbox' = enlarge on click (lightbox effect)
					},
				}),
			});
		}
	);
})();

