<?php get_header(); ?>

<main id="primary" class="site-main">
  <?php while (have_posts()) : the_post(); ?>
  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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
  <?php endwhile; ?>
</main>

<?php
get_footer();
