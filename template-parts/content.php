<?php
/**
 * Template part for displaying content
 *
 * @package CarnavalSF
 */

?>
<div class="entry-content">
	<?php
	// Check if page title should be hidden.
	$hide_title = get_post_meta( get_the_ID(), '_carnavalsf_hide_page_title', true );
	if ( '1' !== $hide_title ) {
		the_title( '<h1 class="entry-title" id="title">', '</h1>' );
	}
	the_content();
	?>
</div>
