<?php
/**
 * The main template file for displaying the blog archive
 *
 * @package selfscan
 */

get_header();

// Get current page number for pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Query for posts
$blog_query = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 9,
    'paged' => $paged,
    'meta_query' => [
        [
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        ]
    ]
]);
?>

<main id="primary" class="site-main">
    <section class="hero-blog">
        <div class="hero-blog__container">
            <div class="hero-blog__content">
                <h1 class="hero-blog__title">
                    <?php esc_html_e('Latest Articles', 'selfscan'); ?>
                </h1>
                <p class="hero-blog__subtitle">
                    <?php esc_html_e('Stay updated with our latest insights, tips, and industry news.', 'selfscan'); ?>
                </p>
            </div>
        </div>
    </section>

    <?php if ($blog_query->have_posts()) : ?>
        <section class="articles">
            <div class="articles__container">
                <div class="articles__grid">
                    <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                        <article class="article-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="article-card__image">
                                    <a href="<?php the_permalink(); ?>" aria-label="<?php printf(esc_attr__('Read more about %s', 'selfscan'), get_the_title()); ?>">
                                        <?php the_post_thumbnail('large', ['class' => 'article-card__img']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="article-card__content">
                                <div class="article-card__meta">
                                    <?php 
                                    $primary_category = selfscan_get_primary_category();
                                    if ($primary_category) : ?>
                                        <span class="article-card__category">
                                            <?php echo esc_html($primary_category->name); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <div class="article-card__details">
                                        <span class="article-card__author">
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/autor-icon.svg'); ?>" alt="<?php esc_attr_e('Author', 'selfscan'); ?>" class="article-card__author-icon">
                                            <?php the_author(); ?>
                                        </span>
                                        <span class="article-card__date">
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/date-icon.svg'); ?>" alt="<?php esc_attr_e('Date', 'selfscan'); ?>" class="article-card__date-icon">
                                            <?php echo esc_html(selfscan_get_post_date()); ?>
                                        </span>
                                        <span class="article-card__read-time">
                                            <?php printf(esc_html__('%d min read', 'selfscan'), selfscan_get_reading_time()); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <h2 class="article-card__title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="article-card__excerpt">
                                    <?php echo esc_html(selfscan_get_excerpt(25)); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="article-card__read-more">
                                    <?php esc_html_e('Read More', 'selfscan'); ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/arrow-right.svg'); ?>" alt="" class="article-card__arrow">
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <?php
                // Pagination
                $total_pages = $blog_query->max_num_pages;
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
        
    <?php else : ?>
        <section class="no-posts">
            <div class="no-posts__container">
                <h2><?php esc_html_e('No articles found', 'selfscan'); ?></h2>
                <p><?php esc_html_e('There are no published articles at the moment. Please check back later.', 'selfscan'); ?></p>
            </div>
        </section>
    <?php endif; ?>
    
    <?php wp_reset_postdata(); ?>
</main>

<?php
get_footer();