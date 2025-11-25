<?php
/**
 * Page Color Meta Box functionality for Carnaval SF theme
 */

class CarnavalSF_Page_Color
{
  public function __construct()
  {
    add_action('add_meta_boxes', [$this, 'add_page_color_meta_box']);
    add_action('save_post', [$this, 'save_page_color']);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
  }

  public function add_page_color_meta_box()
  {
    add_meta_box(
      'carnavalsf_page_color',
      __('Page Color', 'carnavalsf'),
      [$this, 'render_page_color_meta_box'],
      'page',
      'side',
      'default'
    );
  }

  public function render_page_color_meta_box($post)
  {
    wp_nonce_field('carnavalsf_page_color_nonce', 'carnavalsf_page_color_nonce');

    $selected_color = get_post_meta($post->ID, '_carnavalsf_page_color', true);
    if (empty($selected_color)) {
      $selected_color = 'color-1';
    }

    // Get colors from customizer
    $color_1 = get_theme_mod('accent_color_1', '#FFA843');
    $color_2 = get_theme_mod('accent_color_2', '#9C286E');

    ?>
    <div class="carnavalsf-page-color-selector">
      <p><?php _e('Select a page color:', 'carnavalsf'); ?></p>
      <div class="carnavalsf-color-swatches">
        <label class="carnavalsf-color-swatch">
          <input type="radio" name="carnavalsf_page_color" value="color-1" <?php checked($selected_color, 'color-1'); ?>>
          <span class="swatch" style="background-color: <?php echo esc_attr($color_1); ?>;"></span>
          <span class="swatch-label"><?php echo esc_html($color_1); ?></span>
        </label>
        <label class="carnavalsf-color-swatch">
          <input type="radio" name="carnavalsf_page_color" value="color-2" <?php checked($selected_color, 'color-2'); ?>>
          <span class="swatch" style="background-color: <?php echo esc_attr($color_2); ?>;"></span>
          <span class="swatch-label"><?php echo esc_html($color_2); ?></span>
        </label>
      </div>
      <p class="description"><?php _e('This color will be applied to the page background.', 'carnavalsf'); ?></p>
    </div>
    <?php
  }

  public function save_page_color($post_id)
  {
    // Check nonce
    if (!isset($_POST['carnavalsf_page_color_nonce']) ||
        !wp_verify_nonce($_POST['carnavalsf_page_color_nonce'], 'carnavalsf_page_color_nonce')) {
      return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    // Check post type
    if (get_post_type($post_id) !== 'page') {
      return;
    }

    // Save the value
    if (isset($_POST['carnavalsf_page_color'])) {
      $color = sanitize_text_field($_POST['carnavalsf_page_color']);
      if (in_array($color, ['color-1', 'color-2'])) {
        update_post_meta($post_id, '_carnavalsf_page_color', $color);
      } else {
        // Default to color-1 if invalid value
        update_post_meta($post_id, '_carnavalsf_page_color', 'color-1');
      }
    } else {
      // Default to color-1 if no value submitted
      update_post_meta($post_id, '_carnavalsf_page_color', 'color-1');
    }
  }

  public function enqueue_admin_assets($hook)
  {
    // Only load on page edit screens
    if ($hook !== 'post.php' && $hook !== 'post-new.php') {
      return;
    }

    global $post_type;
    if ($post_type !== 'page') {
      return;
    }

    // Enqueue admin CSS
    wp_enqueue_style(
      'carnavalsf-page-color-admin',
      get_template_directory_uri() . '/css/page-color-admin.css',
      [],
      '1.0'
    );

    // Enqueue admin JavaScript
    wp_enqueue_script(
      'carnavalsf-page-color-admin',
      get_template_directory_uri() . '/js/page-color-admin.js',
      ['jquery'],
      '1.0',
      true
    );

    // Localize script with customizer colors
    wp_localize_script('carnavalsf-page-color-admin', 'carnavalsfPageColor', [
      'color1' => get_theme_mod('accent_color_1', '#FFA843'),
      'color2' => get_theme_mod('accent_color_2', '#9C286E'),
    ]);
  }
}

new CarnavalSF_Page_Color();

