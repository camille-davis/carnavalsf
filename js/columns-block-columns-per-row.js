(function () {
	const { addFilter } = wp.hooks;
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, SelectControl } = wp.components;
	const { createHigherOrderComponent } = wp.compose;
	const { Fragment, createElement: el } = wp.element;

	const BLOCK_NAME = 'core/columns';
	const COLUMN_MIN = 2;
	const COLUMN_MAX = 8;

	const columnOptions = Array.from({ length: COLUMN_MAX - COLUMN_MIN + 1 }, (_, i) => ({
		label: String(i + COLUMN_MIN),
		value: String(i + COLUMN_MIN),
	}));

	const selectOptions = [{ label: 'Select...', value: '' }, ...columnOptions];

	addFilter(
		'blocks.registerBlockType',
		'carnavalsf/columns-block-columns-per-row',
		(settings, name) => {
			if (name !== BLOCK_NAME) {
				return settings;
			}

			return Object.assign({}, settings, {
				attributes: Object.assign({}, settings.attributes, {
					desktopColumnsPerRow: {
						type: 'string',
						default: '',
					},
					intermediateColumnsPerRow: {
						type: 'string',
						default: '',
					},
				}),
			});
		}
	);

	const withColumnsPerRowControl = createHigherOrderComponent((BlockEdit) => {
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
						{ title: 'Columns per Row', initialOpen: true, order: 10 },
						el(SelectControl, {
							label: 'Desktop size',
							value: attributes.desktopColumnsPerRow || '',
							options: selectOptions,
							onChange: (value) => setAttributes({ desktopColumnsPerRow: value }),
							help: 'Number of columns per row on desktop screens.',
						}),
						el(SelectControl, {
							label: 'Intermediate size',
							value: attributes.intermediateColumnsPerRow || '',
							options: selectOptions,
							onChange: (value) => setAttributes({ intermediateColumnsPerRow: value }),
							help: 'Number of columns per row under 1200px width.',
						})
					)
				)
			);
		};
	}, 'withColumnsPerRowControl');

	addFilter('editor.BlockEdit', 'carnavalsf/columns-block-columns-per-row', withColumnsPerRowControl);

	addFilter(
		'editor.BlockListBlock',
		'carnavalsf/columns-block-columns-per-row-attributes',
		(BlockListBlock) => {
			return ({ name, attributes, wrapperProps, ...props }) => {
				if (name !== BLOCK_NAME) {
					return el(BlockListBlock, { name, attributes, wrapperProps, ...props });
				}

				const desktop = attributes.desktopColumnsPerRow;
				const intermediate = attributes.intermediateColumnsPerRow || desktop;

				if (!desktop && !intermediate) {
					return el(BlockListBlock, { name, attributes, wrapperProps, ...props });
				}

				return el(BlockListBlock, {
					...props,
					name,
					attributes,
					wrapperProps: {
						...wrapperProps,
						...(desktop && { 'data-columns-per-row-desktop': desktop }),
						...(intermediate && { 'data-columns-per-row-intermediate': intermediate }),
					},
				});
			};
		}
	);
})();

