<?php
/**
 * The sidebar containing the main widget area - Blog Layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package selfscan
 */
?>

<aside class="sidebar-blog-body">
    <div class="sidebar-blog-body__items">
        
        <!-- Categories Section -->
        <div class="sidebar-blog-body__item">
            <h3 class="sidebar-blog-body__title">
                Categories
            </h3>
            <ul class="sidebar-blog-body__categories">
                <?php
                $categories = get_categories( array(
                    'hide_empty' => true,
                    'exclude' => array( 1 ), // Exclude uncategorized
                ) );
                
                if ( ! empty( $categories ) ) :
                    foreach ( $categories as $category ) :
                ?>
                    <li class="sidebar-blog-body__category-item">
                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="sidebar-blog-body__category-link">
                            <div class="sidebar-blog-body__category-name">
                                <?php echo esc_html( $category->name ); ?>
                            </div>
                            <div class="sidebar-blog-body__category-quantity">
                                <div class="sidebar-blog-body__category-value">
                                    <?php echo esc_html( $category->count ); ?>
                                </div>
                                <div class="sidebar-blog-body__category-icon">
                                    &gt;
                                </div>
                            </div>
                        </a>
                    </li>
                <?php
                    endforeach;
                else :
                ?>
                    <li class="sidebar-blog-body__category-item">
                        <span class="sidebar-blog-body__category-name">No categories found.</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Recent Posts Section -->
        <div class="sidebar-blog-body__item">
            <h3 class="sidebar-blog-body__title">
                Recent Posts
            </h3>
            <div class="sidebar-blog-body__posts">
                <?php
                $recent_posts = new WP_Query( array(
                    'posts_per_page' => 3,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                ) );

                if ( $recent_posts->have_posts() ) :
                    while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
                ?>
                    <article class="sidebar-blog-body__post">
                        <a href="<?php the_permalink(); ?>" class="sidebar-blog-body__post-link">
                            <h3 class="sidebar-blog-body__post-title">
                                <?php
                                $title = get_the_title();
                                if ( strlen( $title ) > 60 ) {
                                    echo esc_html( substr( $title, 0, 60 ) . '...' );
                                } else {
                                    echo esc_html( $title );
                                }
                                ?>
                            </h3>
                        </a>
                    </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p>No recent posts found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Popular Tags Section (if tags exist) -->
        <?php
        $tags = get_tags( array(
            'hide_empty' => true,
            'number' => 10,
            'orderby' => 'count',
            'order' => 'DESC',
        ) );
        
        if ( ! empty( $tags ) ) :
        ?>
        <div class="sidebar-blog-body__item">
            <h3 class="sidebar-blog-body__title">
                Popular Tags
            </h3>
            <div class="sidebar-blog-body__tags">
                <ul class="sidebar-blog-body__tag-list">
                    <?php foreach ( $tags as $tag ) : ?>
                        <li class="sidebar-blog-body__tag-item">
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="sidebar-blog-body__tag-link">
                                <?php echo esc_html( $tag->name ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <!-- Newsletter Subscribe Section -->
        <div class="sidebar-blog-body__item">
            <h3 class="sidebar-blog-body__title">
                Stay Updated
            </h3>
            <div class="sidebar-blog-body__subscribe">
                <div class="sidebar-blog-body__subscribe-text">
                    Get the latest insights on security, technology and Self Scan solutions.
                </div>
                <form action="#" class="sidebar-blog-body__subscribe-form" method="post">
                    <div class="sidebar-blog-body__subscribe-item">
                        <input placeholder="Your email address" type="email" class="sidebar-blog-body__subscribe-input" name="subscriber_email" required>
                    </div>
                    <button type="submit" class="sidebar-blog-body__subscribe-button button button-red">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
        
        <!-- WordPress Widget Area Fallback -->
        <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
            <div class="sidebar-blog-body__item">
                <h3 class="sidebar-blog-body__title">Additional</h3>
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
            </div>
        <?php endif; ?>
        
    </div>
</aside><!-- .sidebar-blog-body -->
