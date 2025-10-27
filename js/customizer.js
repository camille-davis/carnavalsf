(function ($) {
  wp.customize.bind('ready', function () {

    // Get all the controls in the typography section.
    typographyControls = wp.customize
      .section('carnavalsf_typography')
      .controls();
    typographyControls.forEach(function (control) {

      // Create and insert 'Reset' button.
      var $resetButton = $(
        '<button type="button" class="button reset-button">' +
          carnavalsfCustomizer.resetText +
          '</button>'
      );
      control.container.find('input').after($resetButton);

      // On reset, repopulate input with default value.
      $resetButton.on('click', function () {
        var defaultValue = control.setting.default;
        control.container.find('input').val(defaultValue).trigger('change');
      });
    });
  });
})(jQuery);
