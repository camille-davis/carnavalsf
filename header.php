<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header class="site-header">

    <!-- Site Logo -->
    <a id="site-logo" href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
      <?php
      $logo_id = get_theme_mod('custom_logo');
      $logo = wp_get_attachment_image_src($logo_id, 'full');
      if ($logo) :
        // Get the image's alt text or fall back to site name
        $alt_text = get_post_meta($logo_id, '_wp_attachment_image_alt', true);
        if (empty($alt_text)) {
          $alt_text = get_bloginfo('name');
        }
      ?>
        <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr($alt_text); ?>">
      <?php else : ?>
        <span class="site-name"><?php bloginfo('name'); ?></span>
      <?php endif; ?>
    </a>

    <!-- Open Menu Button -->
    <button id="open-menu" class="open-menu" aria-label="<?php esc_attr_e('Open Menu', 'carnavalsf'); ?>" aria-expanded="false" aria-owns="menu-outer">
      <span class="open-menu__icon"></span>
      <span class="open-menu__icon"></span>
      <span class="open-menu__icon"></span>
    </button>

    <!-- Navigation Menus -->
    <nav id="menu-outer" class="menu-outer">
      <div class="menu-inner">

        <!-- Close Menu Button -->
        <button id="close-menu" class="close-menu" aria-label="<?php esc_attr_e('Close Menu', 'carnavalsf'); ?>">
          <span class="close-menu__icon"></span>
          <span class="close-menu__icon"></span>
        </button>

        <!-- Navigation Links -->
        <?php wp_nav_menu(array(
          'theme_location' => 'header-1',
          'container' => null,
          'menu_class' => 'menu-links menu-links-1',
        )); ?>
        <?php wp_nav_menu(array(
          'theme_location' => 'header-2',
          'container' => null,
          'menu_class' => 'menu-links menu-links-2',
        )); ?>
      </div>
    </nav>
  </header>
