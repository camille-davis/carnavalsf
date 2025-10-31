jQuery(document).ready(function() {
  const $ = jQuery;

  // Get all details blocks.
  $('.wp-block-details').each(function() {
    const $details = $(this);
    const $summary = $details.find('summary');
    const $content = $details.find('.details-content');
    const contentHeight = $content.outerHeight();
    $summary.on('click', function (e) {

      // Closing details block.
      if ($details.attr('open')) {
        e.preventDefault();
        $content.css('max-height', contentHeight);
        window.setTimeout(() => {
          $content.css('max-height', '0px');
        });
        window.setTimeout(() => {
          $details.removeAttr('open');
          $content.css('max-height', '');
        }, 300);
        return;
      }

      // Opening details block.
      $content.css('max-height', '0px');
      window.setTimeout(() => {
        $content.css('max-height', contentHeight);
      });
      window.setTimeout(() => {
        $content.css('max-height', '');
      }, 300);
    });
  });
});
