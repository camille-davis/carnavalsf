(function () {
	const { addFilter } = wp.hooks;
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl } = wp.components;
	const { createHigherOrderComponent } = wp.compose;
	const { Fragment, createElement: el } = wp.element;

	const BLOCK_NAME = 'core/image';
	const imageAttributes = [
		{ attrKey: 'fullwidth', className: 'is-fullwidth-image', label: 'Fullwidth', title: 'Width', order: 10, initialOpen: true },
		{ attrKey: 'fullheight', className: 'is-fullheight-image', label: 'Fullheight', title: 'Height', order: 11, initialOpen: false },
	];

	imageAttributes.forEach(({ attrKey, className: cssClass, label, title, order, initialOpen }) => {
		addFilter(
			'blocks.registerBlockType',
			`carnavalsf/image-block-${attrKey}`,
			(settings, name) => {
				if (name !== BLOCK_NAME) {
					return settings;
				}

				return Object.assign({}, settings, {
					attributes: Object.assign({}, settings.attributes, {
						[attrKey]: {
							type: 'boolean',
							default: false,
						},
					}),
				});
			}
		);

		const withControl = createHigherOrderComponent((BlockEdit) => {
			return ({ name, attributes, setAttributes, ...props }) => {
				if (name !== BLOCK_NAME) {
					return el(BlockEdit, { name, attributes, setAttributes, ...props });
				}

				return el(
					Fragment,
					{},
					el(BlockEdit, { name, attributes, setAttributes, ...props }),
					el(
						InspectorControls,
						{ group: 'settings' },
						el(
							PanelBody,
							{ title, initialOpen, order },
							el(ToggleControl, {
								label,
								checked: attributes[attrKey],
								onChange: (value) => setAttributes({ [attrKey]: value }),
							})
						)
					)
				);
			};
		}, `with${attrKey.charAt(0).toUpperCase() + attrKey.slice(1)}Control`);

		addFilter(`editor.BlockEdit`, `carnavalsf/image-block-${attrKey}`, withControl);

		addFilter(
			'editor.BlockListBlock',
			`carnavalsf/image-block-${attrKey}-class`,
			(BlockListBlock) => {
				return ({ name, attributes, className, ...props }) => {
					if (name !== BLOCK_NAME || !attributes?.[attrKey]) {
						return el(BlockListBlock, { name, attributes, className, ...props });
					}

					const cssClassName = className ? `${className} ${cssClass}` : cssClass;
					return el(BlockListBlock, {
						...props,
						name,
						attributes,
						className: cssClassName,
					});
				};
			}
		);
	});
})();

