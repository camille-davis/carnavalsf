jQuery(document).ready(function() {
  const $ = jQuery;
  const $menuOpen = $('#open-menu');
  const $menuClose = $('#close-menu');
  const $menuOuter = $('#menu-outer');

  if (!$menuOpen.length || !$menuOuter.length) return;

  function openMenu() {
    if ($menuOpen.attr('aria-expanded') !== 'false') {
      return;
    }
    $menuOpen.attr('aria-expanded', 'true');
    $menuClose.focus();

    const elements = getElementsOutsideMenu();
    elements.forEach(function(el) {
      $(el).attr('tabindex', '-1');
    });
  }

  function closeMenu() {
    if ($menuOpen.attr('aria-expanded') !== 'true') {
      return;
    }
    $menuOpen.attr('aria-expanded', 'false');
    $menuOpen.focus();

    const elements = getElementsOutsideMenu();
    elements.forEach(function(el) {
      $(el).removeAttr('tabindex');
    });
  }

  function getElementsOutsideMenu() {
    let elements = [];

    let $el = $menuOuter;
    while ($el.length > 0 && $el.get(0) !== document.body) {
      elements.push(...$el.siblings());
      $el = $el.parent();
    }

    elements = elements.filter(function(el) {
      return !$(el).is('script') && !$(el).is('#wpadminbar');
    });

    return elements;
  }

  $menuOpen.on('click', openMenu);
  $menuClose.on('click', closeMenu);

  // Close menu when clicking outside.
  $menuOuter.on('click', function(e) {
    if (!$(e.target).closest('.menu-inner').length) {
      closeMenu();
    }
  });

  // Close menu when pressing the Escape key.
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape' && $menuOpen.attr('aria-expanded') === 'true') {
      closeMenu();
    }
  });
});
