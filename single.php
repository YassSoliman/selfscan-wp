<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package selfscan
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        
        <div class="single-post">
            <div class="single-post__container">
                <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="single-post__button button button-grey">
                    <span class="button__icon">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/arrow-right.svg'); ?>" alt="" style="transform: rotate(180deg);">
                    </span>
                    <span class="button__text button__text-big">
                        Back to blog
                    </span>
                </a>
            </div>

            <div class="single-post__container single-post__container-inner">
                <div class="single-post__body body body-big">
                    <section class='single-post-hero' aria-labelledby='single-post-hero-title'>
                        <div class="single-post-hero__header">
                            <?php 
                            $primary_category = selfscan_get_primary_category();
                            if ($primary_category) : ?>
                                <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>" class="single-post-hero__category">
                                    <?php echo esc_html($primary_category->name); ?>
                                </a>
                            <?php endif; ?>
                            <div class="single-post-hero__read-time">
                                <span class="single-post-hero__read-time-value">
                                    <?php echo esc_html(selfscan_get_reading_time()); ?>
                                </span>
                                min read
                            </div>
                        </div>
                        <h1 class="single-post-hero__title title" id="single-post-hero-title">
                            <?php the_title(); ?>
                        </h1>
                    </section>
                    
                    <section class="content-single-post">
                        <div class="content-single-post__article">
                            <div class="header-content-single">
                                <div class="header-content-single__info">
                                    <div class="header-content-single__avatar">
                                        <?php echo selfscan_get_author_avatar(get_the_author_meta('ID'), 48); ?>
                                    </div>
                                    <div class="header-content-single__details">
                                        <div class="header-content-single__name">
                                            <?php the_author(); ?>
                                        </div>
                                        <div class="header-content-single__date">
                                            <div class="header-content-single__date-icon">
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/date-icon.svg'); ?>" alt="">
                                            </div>
                                            <div class="header-content-single__date-text">
                                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                    <?php echo esc_html(get_the_date('F j, Y')); ?>
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="header-content-single__actions">
                                    <?php echo selfscan_get_share_buttons(); ?>
                                </div>
                            </div>
                            
                            <div class="content-single-post__items">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        
        <?php
        // Related posts section
        $related_posts = selfscan_get_related_posts(get_the_ID(), 3);
        if ($related_posts && $related_posts->have_posts()) : ?>
            <section class="related-posts">
                <div class="related-posts__container">
                    <h2 class="related-posts__title"><?php esc_html_e('Related Articles', 'selfscan'); ?></h2>
                    <div class="related-posts__grid">
                        <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                            <article class="related-post">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="related-post__image">
                                        <a href="<?php the_permalink(); ?>" aria-label="<?php printf(esc_attr__('Read more about %s', 'selfscan'), get_the_title()); ?>">
                                            <?php the_post_thumbnail('medium', ['class' => 'related-post__img']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="related-post__content">
                                    <?php 
                                    $primary_category = selfscan_get_primary_category();
                                    if ($primary_category) : ?>
                                        <span class="related-post__category">
                                            <?php echo esc_html($primary_category->name); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <h3 class="related-post__title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <div class="related-post__meta">
                                        <span class="related-post__date"><?php echo esc_html(selfscan_get_post_date()); ?></span>
                                        <span class="related-post__read-time">
                                            <?php printf(esc_html__('%d min read', 'selfscan'), selfscan_get_reading_time()); ?>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php 
        wp_reset_postdata();
        endif; ?>
        
    <?php endwhile; ?>
</main>

<?php
get_footer();
