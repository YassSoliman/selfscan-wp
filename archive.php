<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package selfscan
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<section class="hero-blog">
				<div class="hero-blog__container">
					<div class="hero-blog__content">
						<?php
						the_archive_title( '<h1 class="hero-blog__title">', '</h1>' );
						the_archive_description( '<p class="hero-blog__subtitle">', '</p>' );
						?>
					</div>
				</div>
			</section>

			<section class="articles">
				<div class="articles__container">
					<div class="articles__grid">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * For posts, use the blog template part for consistent styling.
							 */
							if ( get_post_type() === 'post' ) {
								get_template_part( 'template-parts/content', 'blog' );
							} else {
								get_template_part( 'template-parts/content', get_post_type() );
							}

						endwhile;
						?>
					</div>
					
					<?php
					// Pagination
					global $wp_query;
					$total_pages = $wp_query->max_num_pages;
					$paged = get_query_var('paged') ? get_query_var('paged') : 1;
					
					if ($total_pages > 1) : ?>
						<div class="articles__pagination">
							<?php
							echo paginate_links([
								'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
								'format' => '?paged=%#%',
								'current' => max(1, $paged),
								'total' => $total_pages,
								'prev_text' => '&larr; ' . __('Previous', 'selfscan'),
								'next_text' => __('Next', 'selfscan') . ' &rarr;',
								'type' => 'list'
							]);
							?>
						</div>
					<?php endif; ?>
				</div>
			</section>

		else :

			<section class="no-posts">
				<div class="no-posts__container">
					<h2><?php esc_html_e('No articles found', 'selfscan'); ?></h2>
					<p><?php esc_html_e('There are no published articles in this category at the moment. Please check back later.', 'selfscan'); ?></p>
				</div>
			</section>

		endif;
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
