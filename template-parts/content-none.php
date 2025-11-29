<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package CarnavalSF
 */

?>
<div class="entry-content">
	<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'carnavalsf' ); ?></h1>
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms.', 'carnavalsf' ); ?></p>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'carnavalsf' ); ?></p>
		<?php endif; ?>
</div>
