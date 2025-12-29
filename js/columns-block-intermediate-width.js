(function () {
	const { addFilter } = wp.hooks;
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl } = wp.components;
	const { createHigherOrderComponent } = wp.compose;
	const { Fragment, createElement: el } = wp.element;

	const BLOCK_NAME = 'core/columns';
	const CLASS_NAME = 'has-intermediate-width';

	addFilter(
		'blocks.registerBlockType',
		'carnavalsf/columns-block-intermediate-width',
		(settings, name) => {
			if (name !== BLOCK_NAME) {
				return settings;
			}

			return Object.assign({}, settings, {
				attributes: Object.assign({}, settings.attributes, {
					intermediateWidth: {
						type: 'boolean',
						default: false,
					},
				}),
			});
		}
	);

	const withIntermediateWidthControl = createHigherOrderComponent((BlockEdit) => {
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
							label: 'Intermediate width',
							checked: attributes.intermediateWidth,
							onChange: (value) => setAttributes({ intermediateWidth: value }),
							help: 'Sets columns to 2 per row between mobile and 1200px width.',
						})
					)
				)
			);
		};
	}, 'withIntermediateWidthControl');

	addFilter('editor.BlockEdit', 'carnavalsf/columns-block-intermediate-width', withIntermediateWidthControl);

	addFilter(
		'editor.BlockListBlock',
		'carnavalsf/columns-block-intermediate-width-class',
		(BlockListBlock) => {
			return ({ name, attributes, className, ...props }) => {
				if (name !== BLOCK_NAME || !attributes?.intermediateWidth) {
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

