<?php
/**
 * The template for displaying all pages
 *
 * @package CarnavalSF
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();

		// Get page color from meta box.
		$page_color_meta = get_post_meta( get_the_ID(), '_carnavalsf_page_color', true );
		$valid_color     = ! empty( $page_color_meta ) && in_array( $page_color_meta, CarnavalSF_Page_Appearance::PAGE_COLOR_OPTIONS, true ) ? $page_color_meta : CarnavalSF_Page_Appearance::DEFAULT_PAGE_COLOR;
		$page_color      = 'page-color-' . $valid_color;
		?>

		<div id="post-<?php the_ID(); ?>" <?php post_class( $page_color ); ?>>
		<?php if ( has_post_thumbnail() ) :
			$thumbnail_url = get_the_post_thumbnail_url();
			if ( $thumbnail_url ) :
				$escaped_url = esc_url( $thumbnail_url );
			?>
			<div class="featured-image" style="--bg-image: url('<?php echo esc_attr( $escaped_url ); ?>');"></div>
		<?php
			endif;
		endif; ?>

			<?php get_template_part( 'template-parts/content' ); ?>
		</div>

	<?php endwhile; ?>
</main>

<?php
get_footer();
