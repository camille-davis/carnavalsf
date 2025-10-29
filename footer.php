    <footer class="site-footer">
      <div class="footer-columns">
        <?php if (is_active_sidebar('footer-column-1')) : ?>
          <div class="footer-column footer-column-1">
            <?php dynamic_sidebar('footer-column-1'); ?>
          </div>
        <?php endif; ?>
        <?php if (is_active_sidebar('footer-column-2')) : ?>
          <div class="footer-column footer-column-2">
            <?php dynamic_sidebar('footer-column-2'); ?>
          </div>
        <?php endif; ?>
        <?php if (is_active_sidebar('footer-column-3')) : ?>
          <div class="footer-column footer-column-3">
            <?php dynamic_sidebar('footer-column-3'); ?>
          </div>
        <?php endif; ?>
      </div>
      <?php if (is_active_sidebar('footer-bottom')) : ?>
        <div class="footer-bottom">
          <?php dynamic_sidebar('footer-bottom'); ?>
        </div>
      <?php endif; ?>
    </footer>

    <?php wp_footer(); ?>
  </body>
</html>
