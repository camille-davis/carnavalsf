(function () {
	const { addFilter } = wp.hooks;
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl } = wp.components;
	const { createHigherOrderComponent } = wp.compose;
	const { Fragment, createElement: el } = wp.element;

	const BLOCK_NAME = 'core/image';
	const CLASS_NAME = 'fullwidth-image';

	addFilter(
		'blocks.registerBlockType',
		'carnavalsf/image-block-fullwidth',
		(settings, name) => {
			if (name !== BLOCK_NAME) {
				return settings;
			}

			return Object.assign({}, settings, {
				attributes: Object.assign({}, settings.attributes, {
					fullwidth: {
						type: 'boolean',
						default: false,
					},
				}),
			});
		}
	);

	const withFullwidthControl = createHigherOrderComponent((BlockEdit) => {
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
						{ title: 'Width', initialOpen: true, order: 10 },
						el(ToggleControl, {
							label: 'Fullwidth',
							checked: attributes.fullwidth,
							onChange: (value) => setAttributes({ fullwidth: value }),
						})
					)
				)
			);
		};
	}, 'withFullwidthControl');

	addFilter('editor.BlockEdit', 'carnavalsf/image-block-fullwidth', withFullwidthControl);

	addFilter(
		'editor.BlockListBlock',
		'carnavalsf/image-block-fullwidth-class',
		(BlockListBlock) => {
			return ({ name, attributes, className, ...props }) => {
				if (name !== BLOCK_NAME || !attributes?.fullwidth) {
					return el(BlockListBlock, { name, attributes, className, ...props });
				}

				return el(BlockListBlock, {
					...props,
					name,
					attributes,
					className: className ? `${className} ${CLASS_NAME}` : CLASS_NAME,
				});
			};
		}
	);
})();

