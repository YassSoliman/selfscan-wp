<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package selfscan
 */

get_header();
?>

<div class="page">
    <?php if ( is_home() && ! is_front_page() ) : ?>
        <?php
        // Get the featured post (latest post) for hero section
        $hero_post_query = new WP_Query( array(
            'posts_per_page' => 1,
            'post_status' => 'publish',
        ) );
        
        if ( $hero_post_query->have_posts() ) :
            while ( $hero_post_query->have_posts() ) : $hero_post_query->the_post();
        ?>
        <div class='hero-blog section' aria-labelledby='hero-blog-title'>
            <div class='hero-blog__container'>
                <article class="hero-blog__body body-alt">
                    <div class="hero-blog__info">
                        <div class="hero-blog__header header-hero-blog">
                            <div class="header-hero-blog__tag">
                                <?php
                                $primary_category = selfscan_get_primary_category();
                                if ( $primary_category ) {
                                    echo esc_html( $primary_category->name );
                                } else {
                                    echo 'Article';
                                }
                                ?>
                            </div>
                            <div class="header-hero-blog__reading-time">
                                <?php echo selfscan_get_reading_time(); ?> min read
                            </div>
                        </div>
                        <h1 class="hero-blog__title title" id="hero-blog-title">
                            <?php the_title(); ?>
                        </h1>
                        <div class="hero-blog__subtitle">
                            <?php echo selfscan_get_excerpt( 25 ); ?>
                        </div>
                        <div class="footer-hero-blog">
                            <a href="<?php the_permalink(); ?>" class="footer-hero-blog__button button button-red">
                                <span class="button__text button__text-big">
                                    Read More
                                </span>
                                <span class="button__icon">
                                    <svg fill="none" width="20" height="20" viewBox="0 0 20 20">
                                        <path d="M15.2188 11.25H0V8.75H15.2188L8.21875 1.75L10 0L20 10L10 20L8.21875 18.25L15.2188 11.25Z" fill="currentColor"/>
                                    </svg>
                                </span>
                            </a>
                            <div class="footer-hero-blog__items">
                                <div class="footer-hero-blog__item">
                                    <div class="footer-hero-blog__icon">
                                        <svg aria-hidden="true" width="18" height="18" viewBox="0 0 18 18">
                                            <path d="M3.87533 13.25C4.58366 12.7083 5.37533 12.2812 6.25033 11.9687C7.12533 11.6562 8.04199 11.5 9.00033 11.5C9.95866 11.5 10.8753 11.6562 11.7503 11.9687C12.6253 12.2812 13.417 12.7083 14.1253 13.25C14.6114 12.6805 14.9899 12.0347 15.2607 11.3125C15.5316 10.5902 15.667 9.8194 15.667 8.99996C15.667 7.15274 15.0177 5.57982 13.7191 4.28121C12.4205 2.9826 10.8475 2.33329 9.00033 2.33329C7.1531 2.33329 5.58019 2.9826 4.28158 4.28121C2.98296 5.57982 2.33366 7.15274 2.33366 8.99996C2.33366 9.8194 2.46908 10.5902 2.73991 11.3125C3.01074 12.0347 3.38921 12.6805 3.87533 13.25ZM9.00033 9.83329C8.18088 9.83329 7.48991 9.55204 6.92741 8.98954C6.36491 8.42704 6.08366 7.73607 6.08366 6.91663C6.08366 6.09718 6.36491 5.40621 6.92741 4.84371C7.48991 4.28121 8.18088 3.99996 9.00033 3.99996C9.81977 3.99996 10.5107 4.28121 11.0732 4.84371C11.6357 5.40621 11.917 6.09718 11.917 6.91663C11.917 7.73607 11.6357 8.42704 11.0732 8.98954C10.5107 9.55204 9.81977 9.83329 9.00033 9.83329ZM9.00033 17.3333C7.84755 17.3333 6.76421 17.1145 5.75033 16.677C4.73644 16.2395 3.85449 15.6458 3.10449 14.8958C2.35449 14.1458 1.76074 13.2638 1.32324 12.25C0.885742 11.2361 0.666992 10.1527 0.666992 8.99996C0.666992 7.84718 0.885742 6.76385 1.32324 5.74996C1.76074 4.73607 2.35449 3.85413 3.10449 3.10413C3.85449 2.35413 4.73644 1.76038 5.75033 1.32288C6.76421 0.885376 7.84755 0.666626 9.00033 0.666626C10.1531 0.666626 11.2364 0.885376 12.2503 1.32288C13.2642 1.76038 14.1462 2.35413 14.8962 3.10413C15.6462 3.85413 16.2399 4.73607 16.6774 5.74996C17.1149 6.76385 17.3337 7.84718 17.3337 8.99996C17.3337 10.1527 17.1149 11.2361 16.6774 12.25C16.2399 13.2638 15.6462 14.1458 14.8962 14.8958C14.1462 15.6458 13.2642 16.2395 12.2503 16.677C11.2364 17.1145 10.1531 17.3333 9.00033 17.3333ZM9.00033 15.6666C9.73644 15.6666 10.4309 15.559 11.0837 15.3437C11.7364 15.1284 12.3337 14.8194 12.8753 14.4166C12.3337 14.0138 11.7364 13.7048 11.0837 13.4895C10.4309 13.2743 9.73644 13.1666 9.00033 13.1666C8.26421 13.1666 7.56977 13.2743 6.91699 13.4895C6.26421 13.7048 5.66699 14.0138 5.12533 14.4166C5.66699 14.8194 6.26421 15.1284 6.91699 15.3437C7.56977 15.559 8.26421 15.6666 9.00033 15.6666ZM9.00033 8.16663C9.36144 8.16663 9.66005 8.04857 9.89616 7.81246C10.1323 7.57635 10.2503 7.27774 10.2503 6.91663C10.2503 6.55551 10.1323 6.2569 9.89616 6.02079C9.66005 5.78468 9.36144 5.66663 9.00033 5.66663C8.63921 5.66663 8.3406 5.78468 8.10449 6.02079C7.86838 6.2569 7.75033 6.55551 7.75033 6.91663C7.75033 7.27774 7.86838 7.57635 8.10449 7.81246C8.3406 8.04857 8.63921 8.16663 9.00033 8.16663Z" fill="currentColor"/>
                                        </svg>
                                    </div>
                                    <div class="footer-hero-blog__label">
                                        <?php the_author(); ?>
                                    </div>
                                </div>
                                <div class="footer-hero-blog__item">
                                    <div class="footer-hero-blog__icon">
                                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 20 20">
                                            <path d="M4.16667 18.3333C3.70833 18.3333 3.31597 18.1701 2.98958 17.8437C2.66319 17.5173 2.5 17.125 2.5 16.6666V4.99996C2.5 4.54163 2.66319 4.14926 2.98958 3.82288C3.31597 3.49649 3.70833 3.33329 4.16667 3.33329H5V1.66663H6.66667V3.33329H13.3333V1.66663H15V3.33329H15.8333C16.2917 3.33329 16.684 3.49649 17.0104 3.82288C17.3368 4.14926 17.5 4.54163 17.5 4.99996V16.6666C17.5 17.125 17.3368 17.5173 17.0104 17.8437C16.684 18.1701 16.2917 18.3333 15.8333 18.3333H4.16667ZM4.16667 16.6666H15.8333V8.33329H4.16667V16.6666ZM4.16667 6.66663H15.8333V4.99996H4.16667V6.66663Z" fill="currentColor"/>
                                        </svg>
                                    </div>
                                    <div class="footer-hero-blog__label">
                                        <time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo selfscan_get_post_date(); ?></time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-blog__decor">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'alt' => 'blog page hero decor' ) ); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/img/blog-page/hero/decor.jpg" alt="blog page hero decor">
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        </div>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    <?php endif; ?>

    <div class="blog-body">
        <div class="blog-body__container">
            <main class="articles-blog-body">
                <div class="articles-blog-body__header">
                    <h2 class="articles-blog-body__title">
                        Latest Articles
                    </h2>
                    <div class="articles-blog-body__quantity">
                        <span class="articles-blog-body__counter"><?php echo wp_count_posts()->publish; ?></span>
                        <?php echo wp_count_posts()->publish == 1 ? 'article' : 'articles'; ?>
                    </div>
                </div>
                <div class="articles-blog-body__content">
                    <?php
                    if ( have_posts() ) :
                        // Skip first post if we're on blog home page (it's already shown in hero)
                        $skip_first = is_home() && ! is_front_page();
                        $post_count = 0;
                        $has_additional_posts = false;
                        
                        while ( have_posts() ) :
                            the_post();
                            $post_count++;
                            
                            // Skip first post on blog home page
                            if ( $skip_first && $post_count === 1 ) {
                                continue;
                            }
                            
                            $has_additional_posts = true;
                            get_template_part( 'template-parts/content', 'blog' );
                        endwhile;

                        // Show "No additional articles" if we only have the hero post
                        if ( ! $has_additional_posts && $skip_first ) :
                            ?>
                            <p>No additional articles found.</p>
                            <?php
                        endif;

                        // Show pagination only if we have navigation
                        if ( get_next_posts_link() || get_previous_posts_link() ) :
                        ?>
                        <div class="articles-blog-body__more">
                            <?php
                            the_posts_navigation( array(
                                'prev_text' => '<span class="button__text button__text-big">Previous Articles</span><span class="button__icon"><svg fill="none"><use xlink:href="' . get_template_directory_uri() . '/img/icons/icons-sprite.svg#arrow-left"></use></svg></span>',
                                'next_text' => '<span class="button__text button__text-big">Load More Articles</span><span class="button__icon"><svg fill="none"><use xlink:href="' . get_template_directory_uri() . '/img/icons/icons-sprite.svg#arrow-right"></use></svg></span>',
                                'screen_reader_text' => 'Blog Navigation'
                            ) );
                            ?>
                        </div>
                        <?php
                        endif;
                    else :
                        ?>
                        <p>No articles found.</p>
                        <?php
                    endif;
                    ?>
                </div>
            </main>
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
get_footer();
