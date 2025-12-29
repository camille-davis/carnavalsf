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
					mediumColumnsPerRow: {
						type: 'string',
						default: '',
					},
					tabletColumnsPerRow: {
						type: 'string',
						default: '',
					},
					mobileColumnsPerRow: {
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
							label: 'Desktop',
							value: attributes.desktopColumnsPerRow || '',
							options: selectOptions,
							onChange: (value) => setAttributes({ desktopColumnsPerRow: value }),
						}),
						el(SelectControl, {
							label: 'Medium',
							value: attributes.mediumColumnsPerRow || '',
							options: selectOptions,
							onChange: (value) => setAttributes({ mediumColumnsPerRow: value }),
						}),
						el(SelectControl, {
							label: 'Tablet',
							value: attributes.tabletColumnsPerRow || '',
							options: selectOptions,
							onChange: (value) => setAttributes({ tabletColumnsPerRow: value }),
						}),
						el(SelectControl, {
							label: 'Mobile',
							value: attributes.mobileColumnsPerRow || '',
							options: selectOptions,
							onChange: (value) => setAttributes({ mobileColumnsPerRow: value }),
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
				const medium = attributes.mediumColumnsPerRow || desktop;
				const tablet = attributes.tabletColumnsPerRow || medium;
				const mobile = attributes.mobileColumnsPerRow || tablet;

				if (!desktop && !medium && !tablet && !mobile) {
					return el(BlockListBlock, { name, attributes, wrapperProps, ...props });
				}

				return el(BlockListBlock, {
					...props,
					name,
					attributes,
					wrapperProps: {
						...wrapperProps,
						...(desktop && { 'data-columns-per-row-desktop': desktop }),
						...(medium && { 'data-columns-per-row-medium': medium }),
						...(tablet && { 'data-columns-per-row-tablet': tablet }),
						...(mobile && { 'data-columns-per-row-mobile': mobile }),
					},
				});
			};
		}
	);
})();

