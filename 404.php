<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package CarnavalSF
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="error-404 not-found">
		<div class="entry-content">
			<h1 class="entry-title"><?php esc_html_e( 'Page not found', 'carnavalsf' ); ?></h1>
			<p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
					<?php esc_html_e( 'Return to Homepage', 'carnavalsf' ); ?>
				</a>
			</p>
		</div>
	</div>
</main>

<?php
get_sidebar();
get_footer();

