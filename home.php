<?php
/**
 * The main template file for displaying the blog archive
 *
 * @package selfscan
 */

get_header();

// Get featured post from ACF settings
$featured_post = selfscan_get_featured_blog_post();

// Get posts per page setting from ACF
$posts_per_page = selfscan_get_blog_posts_per_page();

// Prepare exclude array for featured post
$exclude_posts = [];
if ($featured_post) {
    $exclude_posts[] = $featured_post->ID;
}

// Query for regular posts (chronological order, most recent first)
$regular_posts_query = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'orderby' => 'date',
    'order' => 'DESC',
    'post__not_in' => $exclude_posts,
    'meta_query' => [
        [
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        ]
    ]
]);

$regular_posts = $regular_posts_query->posts;

// Get total count for "Load more" button (excluding featured post)
$total_posts_query = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'post__not_in' => $exclude_posts,
    'meta_query' => [
        [
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        ]
    ],
    'fields' => 'ids'
]);

$total_posts = $total_posts_query->found_posts;

// Get total count of ALL published posts on the website (for the counter)
$total_website_posts_query = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => [
        [
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        ]
    ],
    'fields' => 'ids'
]);

$total_website_posts = $total_website_posts_query->found_posts;

// If no featured post is selected, use the most recent post as featured
if (!$featured_post && !empty($regular_posts)) {
    $featured_post = $regular_posts[0];
    // Remove the featured post from regular posts array
    $regular_posts = array_slice($regular_posts, 1);
    $total_posts--; // Adjust total count
}
?>

<main id="primary" class="site-main">
    <?php if ($featured_post) : ?>
        <div class='hero-blog section' aria-labelledby='hero-blog-title'>
            <div class='hero-blog__container'>
                <article class="hero-blog__body body-alt">
                    <div class="hero-blog__info">
                        <div class="hero-blog__header header-hero-blog">
                            <?php 
                            $primary_category = get_the_category($featured_post->ID);
                            if (!empty($primary_category)) : ?>
                                <a href="<?php echo esc_url(get_category_link($primary_category[0]->term_id)); ?>" class="header-hero-blog__tag">
                                    <?php echo esc_html($primary_category[0]->name); ?>
                                </a>
                            <?php endif; ?>
                            <div class="header-hero-blog__reading-time">
                                <?php echo esc_html(selfscan_get_reading_time($featured_post->ID)); ?> min read
                            </div>
                        </div>
                        <h1 class="hero-blog__title title" id="hero-blog-title">
                            <?php echo esc_html($featured_post->post_title); ?>
                        </h1>
                        <div class="hero-blog__subtitle">
                            <?php echo esc_html(selfscan_get_excerpt(20, $featured_post->ID)); ?>
                        </div>
                        <div class="footer-hero-blog">
                            <a href="<?php echo esc_url(get_permalink($featured_post->ID)); ?>" class="footer-hero-blog__button button button-red">
                                <span class="button__text button__text-big">
                                    Read More
                                </span>
                                <span class="button__icon">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/arrow-right.svg'); ?>" alt="">
                                </span>
                            </a>
                            <div class="footer-hero-blog__items">
                                <div class="footer-hero-blog__item" id="main-article-autor">
                                    <div class="footer-hero-blog__icon">
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/autor-icon.svg'); ?>" alt="">
                                    </div>
                                    <div class="footer-hero-blog__label">
                                        <?php echo esc_html(get_the_author_meta('display_name', $featured_post->post_author)); ?>
                                    </div>
                                </div>
                                <div class="footer-hero-blog__item" id="main-article-date-published">
                                    <div class="footer-hero-blog__icon">
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/date-icon.svg'); ?>" alt="">
                                    </div>
                                    <div class="footer-hero-blog__label">
                                        <time datetime="<?php echo esc_attr(get_the_date('c', $featured_post->ID)); ?>">
                                            <?php echo esc_html(get_the_date('F j, Y', $featured_post->ID)); ?>
                                        </time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-blog__decor">
                        <?php if (has_post_thumbnail($featured_post->ID)) : ?>
                            <?php echo get_the_post_thumbnail($featured_post->ID, 'large', ['alt' => 'blog page hero decor']); ?>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        </div>
    <?php endif; ?>

    <div class="blog-body">
        <div class="blog-body__container">
            <main class="articles-blog-body">
                <div class="articles-blog-body__header">
                    <h2 class="articles-blog-body__title">
                        Latest Articles
                    </h2>
                    <div class="articles-blog-body__quantity">
                        <span class="articles-blog-body__counter"><?php echo esc_html($total_website_posts); ?></span>
                        <?php echo esc_html(_n('article', 'articles', $total_website_posts, 'selfscan')); ?>
                    </div>
                </div>
                
                <?php if (!empty($regular_posts)) : ?>
                    <div class="articles-blog-body__content">
                        <?php foreach ($regular_posts as $post) : setup_postdata($post); ?>
                            <article class="articles-blog-body__article">
                                <a href="<?php echo esc_url(get_permalink()); ?>" class="articles-blog-body__card-link">
                                    <div class="articles-blog-body__info">
                                        <div class="articles-blog-body__content">
                                            <h3 class="articles-blog-body__label">
                                                <?php the_title(); ?>
                                            </h3>
                                        </div>
                                        <div class="articles-blog-body__text">
                                            <?php echo esc_html(selfscan_get_excerpt(25)); ?>
                                        </div>
                                        <div class="articles-blog-body__footer">
                                            <div class="articles-blog-body__items">
                                                <div class="articles-blog-body__item" data-article-autor>
                                                    <div class="articles-blog-body__icon">
                                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/autor-icon.svg'); ?>" alt="">
                                                    </div>
                                                    <div class="articles-blog-body__value">
                                                        <?php the_author(); ?>
                                                    </div>
                                                </div>
                                                <div class="articles-blog-body__item" data-article-date-published>
                                                    <div class="articles-blog-body__icon">
                                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/date-icon.svg'); ?>" alt="">
                                                    </div>
                                                    <div class="articles-blog-body__value">
                                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                            <?php echo esc_html(get_the_date('F j, Y')); ?>
                                                        </time>
                                                    </div>
                                                </div>
                                                <div class="articles-blog-body__item" data-article-reading-time>
                                                    <div class="articles-blog-body__value">
                                                        <?php echo esc_html(selfscan_get_reading_time()); ?> min read
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles-blog-body__decor">
                                        <?php 
                                        $primary_category = selfscan_get_primary_category();
                                        if ($primary_category) : ?>
                                            <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>" class="articles-blog-body__category">
                                                <?php echo esc_html($primary_category->name); ?>
                                            </a>
                                        <?php endif; ?>
                                        <div class="articles-blog-body__image">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('medium', ['loading' => 'lazy', 'alt' => 'article-image']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                    
                    <?php 
                    $show_load_more = selfscan_show_load_more_button();
                    $current_displayed = count($regular_posts);
                    $offset = $current_displayed + 1; // +1 for featured post
                    
                    if ($show_load_more && $total_posts > $current_displayed) : ?>
                        <div class="articles-blog-body__more">
                            <button type="button" class="articles-blog-body__button button button-transparent" id="load-more-articles" data-offset="<?php echo esc_attr($offset); ?>">
                                <span class="button__text button__text-big">
                                    Load more articles
                                </span>
                                <span class="button__icon">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/arrow-right.svg'); ?>" alt="">
                                </span>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="articles-blog-body__content">
                        <p><?php esc_html_e('No additional articles found.', 'selfscan'); ?></p>
                    </div>
                <?php endif; ?>
            </main>
            
            <aside class="sidebar-blog-body">
                <div class="sidebar-blog-body__items">
                    <?php
                    // Get categories with post counts
                    $categories = get_categories([
                        'hide_empty' => true,
                        'number' => 10
                    ]);
                    
                    if (!empty($categories)) : ?>
                        <div class="sidebar-blog-body__item">
                            <h3 class="sidebar-blog-body__title">
                                Categories
                            </h3>
                            <ul class="sidebar-blog-body__categories">
                                <?php foreach ($categories as $category) : ?>
                                    <li class="sidebar-blog-body__category-item">
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="sidebar-blog-body__category-link">
                                            <div class="sidebar-blog-body__category-name">
                                                <?php echo esc_html($category->name); ?>
                                            </div>
                                            <div class="sidebar-blog-body__category-quantity">
                                                <div class="sidebar-blog-body__category-value">
                                                    <?php echo esc_html($category->count); ?>
                                                </div>
                                                <div class="sidebar-blog-body__category-icon">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    // Get recent posts
                    $recent_posts = get_posts([
                        'numberposts' => 3,
                        'post_status' => 'publish',
                        'meta_query' => [
                            [
                                'key' => '_thumbnail_id',
                                'compare' => 'EXISTS'
                            ]
                        ]
                    ]);
                    
                    if (!empty($recent_posts)) : ?>
                        <div class="sidebar-blog-body__item">
                            <h3 class="sidebar-blog-body__title">
                                Recent Posts
                            </h3>
                            <div class="sidebar-blog-body__posts">
                                <?php foreach ($recent_posts as $recent_post) : ?>
                                    <article class="sidebar-blog-body__post">
                                        <a href="<?php echo esc_url(get_permalink($recent_post->ID)); ?>" class="sidebar-blog-body__post-link">
                                            <h3 class="sidebar-blog-body__post-title">
                                                <?php echo esc_html(wp_trim_words($recent_post->post_title, 10, '...')); ?>
                                            </h3>
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    // Get popular tags
                    $popular_tags = get_tags([
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 8,
                        'hide_empty' => true
                    ]);
                    
                    if (!empty($popular_tags)) : ?>
                        <div class="sidebar-blog-body__item">
                            <h3 class="sidebar-blog-body__title">
                                Popular Tags
                            </h3>
                            <div class="sidebar-blog-body__tags">
                                <ul class="sidebar-blog-body__tag-list">
                                    <?php foreach ($popular_tags as $tag) : ?>
                                        <li class="sidebar-blog-body__tag-item">
                                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="sidebar-blog-body__tag-link">
                                                <?php echo esc_html($tag->name); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="sidebar-blog-body__item">
                        <h3 class="sidebar-blog-body__title">
                            Stay Updated
                        </h3>
                        <div class="sidebar-blog-body__subscribe">
                            <div class="sidebar-blog-body__subscribe-text">
                                Get the latest insights on security, technology and Self Scan solutions.
                            </div>
                            <form action="#" class="sidebar-blog-body__subscribe-form">
                                <div class="sidebar-blog-body__subscribe-item">
                                    <input placeholder="Your email address" type="email" class="sidebar-blog-body__subscribe-input">
                                </div>
                                <button type="submit" class="sidebar-blog-body__subscribe-button button button-red">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    
    <?php wp_reset_postdata(); ?>
</main>

<?php
get_footer();