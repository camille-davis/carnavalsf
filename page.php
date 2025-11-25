<?php get_header();

while (have_posts()): the_post();
  $page_color = 'page-color-1';
  $page_color_meta = get_post_meta(get_the_ID(), '_carnavalsf_page_color', true);
  if (!empty($page_color_meta) && in_array($page_color_meta, ['color-1', 'color-2'])) {
    $page_color = 'page-color-' . $page_color_meta;
  }
  ?>

  <main id="primary" class="site-main">
    <div id="post-<?php the_ID(); ?>" <?php post_class($page_color); ?>>

      <!-- Featured image -->
      <?php if (has_post_thumbnail()) : ?>
        <div class="featured-image" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">
        </div>
      <?php endif; ?>

      <div class="entry-content">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        <?php the_content(); ?>
      </div>
    </div>
  </main>

<?php endwhile;
get_footer();
