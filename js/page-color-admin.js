(function ($) {
  'use strict';

  /**
   * Update color swatches from localized data
   */
  $(document).ready(function () {
    if (typeof carnavalsfPageColor !== 'undefined') {
      $('.carnavalsf-color-swatch input[value="color-1"]')
        .siblings('.swatch')
        .css('background-color', carnavalsfPageColor.color1);

      $('.carnavalsf-color-swatch input[value="color-2"]')
        .siblings('.swatch')
        .css('background-color', carnavalsfPageColor.color2);
    }
  });
})(jQuery);

