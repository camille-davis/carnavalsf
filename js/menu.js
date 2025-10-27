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

    // Open the menu.
    $menuOuter.addClass('transitioning');
    $menuOpen.attr('aria-expanded', 'true');
    setTimeout(() => {
      $menuOuter.removeClass('transitioning');
    }, 300);

    // Handle focus.
    const elements = getElementsOutsideMenu();
    elements.forEach(function(el) {
      $(el).attr('tabindex', '-1');
    });
    $menuClose.focus();
  }

  function closeMenu() {
    if ($menuOpen.attr('aria-expanded') !== 'true') {
      return;
    }

    // Close the menu.
    $menuOuter.addClass('transitioning');
    $menuOpen.attr('aria-expanded', 'false');
    setTimeout(() => {
      $menuOuter.removeClass('transitioning');
    }, 300);

    // Handle focus.
    const elements = getElementsOutsideMenu();
    elements.forEach(function(el) {
      $(el).removeAttr('tabindex');
    });
    $menuOpen.focus();
  }

  function getElementsOutsideMenu() {
    let elements = [];

    // Get menu siblings, and siblings of menu parents up to the body.
    let $el = $menuOuter;
    while ($el.length > 0 && $el.get(0) !== document.body) {
      elements.push(...$el.siblings());
      $el = $el.parent();
    }

    // Remove script elements and admin bar.
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

  // Close menu when resizing to desktop size.
  $(window).on('resize', function() {
    if ($(window).width() >= 1080) {
      closeMenu();
    }
  });
});
