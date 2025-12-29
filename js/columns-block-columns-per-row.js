(function () {
	const { addFilter } = wp.hooks;
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, SelectControl } = wp.components;
	const { createHigherOrderComponent } = wp.compose;
	const { Fragment, createElement: el } = wp.element;

	const BLOCK_NAME = 'core/columns';
	const COLUMN_MIN = 2;
	const COLUMN_MAX = 8;

	// Generate options for column range
	const columnOptions = Array.from({ length: COLUMN_MAX - COLUMN_MIN + 1 }, (_, i) => ({
		label: String(i + COLUMN_MIN),
		value: String(i + COLUMN_MIN),
	}));

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

			const desktopValue = attributes.desktopColumnsPerRow || '';
			const intermediateValue = attributes.intermediateColumnsPerRow || desktopValue;
			const intermediateOptions = [
				{ label: desktopValue ? `Default (${desktopValue})` : 'Select...', value: '' },
				...columnOptions,
			];

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
							value: desktopValue,
							options: [
								{ label: 'Select...', value: '' },
								...columnOptions,
							],
							onChange: (value) => setAttributes({ desktopColumnsPerRow: value }),
							help: 'Number of columns per row on desktop screens.',
						}),
						el(SelectControl, {
							label: 'Intermediate size',
							value: attributes.intermediateColumnsPerRow || '',
							options: intermediateOptions,
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

				const desktopValue = attributes.desktopColumnsPerRow;
				const intermediateValue = attributes.intermediateColumnsPerRow || desktopValue;

				if (!desktopValue && !intermediateValue) {
					return el(BlockListBlock, { name, attributes, wrapperProps, ...props });
				}

				return el(BlockListBlock, {
					...props,
					name,
					attributes,
					wrapperProps: {
						...wrapperProps,
						...(desktopValue && { 'data-columns-per-row-desktop': desktopValue }),
						...(intermediateValue && { 'data-columns-per-row-intermediate': intermediateValue }),
					},
				});
			};
		}
	);
})();

