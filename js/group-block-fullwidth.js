(function() {
  const { addFilter } = wp.hooks;
  const { InspectorControls } = wp.blockEditor;
  const { PanelBody, ToggleControl } = wp.components;
  const { createHigherOrderComponent } = wp.compose;
  const { Fragment, createElement } = wp.element;

  // Add the fullwidth attribute to the core/group block
  addFilter(
    'blocks.registerBlockType',
    'carnavalsf/group-block-fullwidth',
    (settings, name) => {
      if (name !== 'core/group') {
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

  // Add the control to the InspectorControls
  const withFullwidthControl = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
      const { name, attributes, setAttributes } = props;

      if (name !== 'core/group') {
        return createElement(BlockEdit, props);
      }

      const { fullwidth } = attributes;

      return createElement(
        Fragment,
        {},
        createElement(BlockEdit, props),
        createElement(
          InspectorControls,
          { group: 'styles' },
          createElement(
            PanelBody,
            { title: 'Width', initialOpen: true, order: 30 },
            createElement(ToggleControl, {
              label: 'Fullwidth',
              checked: fullwidth,
              onChange: function(value) {
                setAttributes({ fullwidth: value });
              },
            })
          )
        )
      );
    };
  }, 'withFullwidthControl');

  addFilter(
    'editor.BlockEdit',
    'carnavalsf/group-block-fullwidth',
    withFullwidthControl
  );
})();

