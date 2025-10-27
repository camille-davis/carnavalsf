<section class="no-results not-found">
  <header class="page-header">
    <h1 class="page-title"><?php esc_html_e('Nothing Found', 'carnavalsf'); ?></h1>
  </header>
  
  <div class="page-content">
    <?php
    if (is_search()) : ?>
      <p><?php esc_html_e('Sorry, but nothing matched your search terms.', 'carnavalsf'); ?></p>
    <?php else : ?>
      <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'carnavalsf'); ?></p>
    <?php endif; ?>
  </div>
</section>
