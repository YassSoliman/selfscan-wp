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
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
            <header class="single-post__header">
                <div class="single-post__container">
                    <?php 
                    $primary_category = selfscan_get_primary_category();
                    if ($primary_category) : ?>
                        <span class="single-post__category">
                            <?php echo esc_html($primary_category->name); ?>
                        </span>
                    <?php endif; ?>
                    
                    <h1 class="single-post__title"><?php the_title(); ?></h1>
                    
                    <div class="single-post__meta">
                        <div class="single-post__author">
                            <?php echo selfscan_get_author_avatar(get_the_author_meta('ID'), 40); ?>
                            <div class="single-post__author-info">
                                <span class="single-post__author-name"><?php the_author(); ?></span>
                                <div class="single-post__details">
                                    <span class="single-post__date"><?php echo esc_html(selfscan_get_post_date()); ?></span>
                                    <span class="single-post__separator">•</span>
                                    <span class="single-post__read-time">
                                        <?php printf(esc_html__('%d min read', 'selfscan'), selfscan_get_reading_time()); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="single-post__share">
                            <?php echo selfscan_get_share_buttons(); ?>
                        </div>
                    </div>
                </div>
            </header>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="single-post__featured-image">
                    <?php the_post_thumbnail('full', ['class' => 'single-post__image']); ?>
                </div>
            <?php endif; ?>
            
            <div class="single-post__content">
                <div class="single-post__container">
                    <div class="single-post__body">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
            
            <?php
            $tags = get_the_tags();
            if ($tags) : ?>
                <footer class="single-post__footer">
                    <div class="single-post__container">
                        <div class="single-post__tags">
                            <span class="single-post__tags-label"><?php esc_html_e('Tags:', 'selfscan'); ?></span>
                            <div class="single-post__tags-list">
                                <?php foreach ($tags as $tag) : ?>
                                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="single-post__tag">
                                        #<?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </footer>
            <?php endif; ?>
        </article>
        
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
        
        <nav class="post-navigation">
            <div class="post-navigation__container">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                
                if ($prev_post || $next_post) : ?>
                    <div class="post-navigation__links">
                        <?php if ($prev_post) : ?>
                            <div class="post-navigation__prev">
                                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="post-navigation__link">
                                    <span class="post-navigation__direction">
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/arrow-right.svg'); ?>" alt="" class="post-navigation__arrow post-navigation__arrow--prev">
                                        <?php esc_html_e('Previous Article', 'selfscan'); ?>
                                    </span>
                                    <span class="post-navigation__title"><?php echo esc_html($prev_post->post_title); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="post-navigation__next">
                                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="post-navigation__link">
                                    <span class="post-navigation__direction">
                                        <?php esc_html_e('Next Article', 'selfscan'); ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/arrow-right.svg'); ?>" alt="" class="post-navigation__arrow">
                                    </span>
                                    <span class="post-navigation__title"><?php echo esc_html($next_post->post_title); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
        
    <?php endwhile; ?>
</main>

<?php
get_footer();
