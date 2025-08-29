<?php
/**
 * Template part for displaying blog posts in archive views
 *
 * @package selfscan
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('article-card'); ?>>
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