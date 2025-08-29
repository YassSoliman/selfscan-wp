<?php
/**
 * Template Name: Pricing Page
 *
 * @package SelfScan
 */

get_header();
?>

<section class="pricing-hero section" aria-labelledby="pricing-hero-title">
    <div class="pricing-hero__container">
        <div class="pricing-hero__body body">
            <h1 class="pricing-hero__title title title-medium" id="pricing-hero-title">
                <?php echo esc_html(get_field('pricing_hero_title') ?: 'Transparent Pricing for Your Canadian Background Check'); ?>
            </h1>
            <div class="pricing-hero__price">
                <?php echo esc_html(get_field('pricing_price') ?: '$59.99'); ?>
            </div>
            <div class="pricing-hero__subtitle">
                <?php echo esc_html(get_field('pricing_subtitle') ?: 'Get fast, secure, and accurate criminal background checks from a trusted Canadian provider. Our service searches adult convictions listed in the Canadian Police Information Centre (CPIC) and other national police databases.'); ?>
            </div>
            <?php 
            $cta_button = get_field('pricing_button');
            
            // Use the CTA button template part
            get_template_part('template-parts/cta-button', null, array(
                'cta_button' => $cta_button,
            ));
            ?>
        </div>
    </div>
</section>

<?php
// Article Section
$featured_article = get_field('featured_article');
if ($featured_article) :
    $post = $featured_article;
    setup_postdata($post);
    
    // Get article data
    $article_title = get_the_title();
    $article_content = has_excerpt() ? get_the_excerpt() : get_the_content();
    
    // Truncate content to ~297 characters
    $article_excerpt = wp_trim_words($article_content, 50, '...');
    if (strlen($article_excerpt) > 297) {
        $article_excerpt = substr($article_excerpt, 0, 294) . '...';
    }
    
    $article_date = get_the_date('F j, Y');
    $article_author = get_the_author();
    $article_link = get_permalink();
    $featured_image_id = get_post_thumbnail_id();
    
    // Get first category for tag
    $categories = get_the_category();
    $article_category = !empty($categories) ? $categories[0]->name : 'Article';
    
    wp_reset_postdata();
?>
<div class='home-article section'>
    <div class='home-article__container'>
        <article class="articles-blog-body__article articles-blog-body__article-alt">
            <div class="articles-blog-body__info">
                <h3 class="articles-blog-body__label">
                    <?php echo esc_html($article_title); ?>
                </h3>
                <div class="articles-blog-body__text">
                    <?php echo esc_html($article_excerpt); ?>
                </div>
                <div class="articles-blog-body__footer articles-blog-body__footer-alt">
                    <a href="<?php echo esc_url($article_link); ?>" class="articles-blog-body__button button">
                        Read More
                    </a>
                    <div class="articles-blog-body__items">
                        <div class="articles-blog-body__item" data-article-autor>
                            <div class="articles-blog-body__icon">
                                <svg aria-hidden="true" viewBox="0 0 18 18" fill="currentColor">
                                    <path d="M3.87533 13.25C4.58366 12.7083 5.37533 12.2812 6.25033 11.9687C7.12533 11.6562 8.04199 11.5 9.00033 11.5C9.95866 11.5 10.8753 11.6562 11.7503 11.9687C12.6253 12.2812 13.417 12.7083 14.1253 13.25C14.6114 12.6805 14.9899 12.0347 15.2607 11.3125C15.5316 10.5902 15.667 9.8194 15.667 8.99996C15.667 7.15274 15.0177 5.57982 13.7191 4.28121C12.4205 2.9826 10.8475 2.33329 9.00033 2.33329C7.1531 2.33329 5.58019 2.9826 4.28158 4.28121C2.98296 5.57982 2.33366 7.15274 2.33366 8.99996C2.33366 9.8194 2.46908 10.5902 2.73991 11.3125C3.01074 12.0347 3.38921 12.6805 3.87533 13.25ZM9.00033 9.83329C8.18088 9.83329 7.48991 9.55204 6.92741 8.98954C6.36491 8.42704 6.08366 7.73607 6.08366 6.91663C6.08366 6.09718 6.36491 5.40621 6.92741 4.84371C7.48991 4.28121 8.18088 3.99996 9.00033 3.99996C9.81977 3.99996 10.5107 4.28121 11.0732 4.84371C11.6357 5.40621 11.917 6.09718 11.917 6.91663C11.917 7.73607 11.6357 8.42704 11.0732 8.98954C10.5107 9.55204 9.81977 9.83329 9.00033 9.83329ZM9.00033 17.3333C7.84755 17.3333 6.76421 17.1145 5.75033 16.677C4.73644 16.2395 3.85449 15.6458 3.10449 14.8958C2.35449 14.1458 1.76074 13.2638 1.32324 12.25C0.885742 11.2361 0.666992 10.1527 0.666992 8.99996C0.666992 7.84718 0.885742 6.76385 1.32324 5.74996C1.76074 4.73607 2.35449 3.85413 3.10449 3.10413C3.85449 2.35413 4.73644 1.76038 5.75033 1.32288C6.76421 0.885376 7.84755 0.666626 9.00033 0.666626C10.1531 0.666626 11.2364 0.885376 12.2503 1.32288C13.2642 1.76038 14.1462 2.35413 14.8962 3.10413C15.6462 3.85413 16.2399 4.73607 16.6774 5.74996C17.1149 6.76385 17.3337 7.84718 17.3337 8.99996C17.3337 10.1527 17.1149 11.2361 16.6774 12.25C16.2399 13.2638 15.6462 14.1458 14.8962 14.8958C14.1462 15.6458 13.2642 16.2395 12.2503 16.677C11.2364 17.1145 10.1531 17.3333 9.00033 17.3333ZM9.00033 15.6666C9.73644 15.6666 10.4309 15.559 11.0837 15.3437C11.7364 15.1284 12.3337 14.8194 12.8753 14.4166C12.3337 14.0138 11.7364 13.7048 11.0837 13.4895C10.4309 13.2743 9.73644 13.1666 9.00033 13.1666C8.26421 13.1666 7.56977 13.2743 6.91699 13.4895C6.26421 13.7048 5.66699 14.0138 5.12533 14.4166C5.66699 14.8194 6.26421 15.1284 6.91699 15.3437C7.56977 15.559 8.26421 15.6666 9.00033 15.6666ZM9.00033 8.16663C9.36144 8.16663 9.66005 8.04857 9.89616 7.81246C10.1323 7.57635 10.2503 7.27774 10.2503 6.91663C10.2503 6.55551 10.1323 6.2569 9.89616 6.02079C9.66005 5.78468 9.36144 5.66663 9.00033 5.66663C8.63921 5.66663 8.3406 5.78468 8.10449 6.02079C7.86838 6.2569 7.75033 6.55551 7.75033 6.91663C7.75033 7.27774 7.86838 7.57635 8.10449 7.81246C8.3406 8.04857 8.63921 8.16663 9.00033 8.16663Z"/>
                                </svg>
                            </div>
                            <div class="articles-blog-body__value">
                                <?php echo esc_html($article_author); ?>
                            </div>
                        </div>
                        <div class="articles-blog-body__item" data-article-date-published>
                            <div class="articles-blog-body__icon">
                                <svg aria-hidden="true" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4.16667 18.3333C3.70833 18.3333 3.31597 18.1701 2.98958 17.8437C2.66319 17.5173 2.5 17.125 2.5 16.6666V4.99996C2.5 4.54163 2.66319 4.14926 2.98958 3.82288C3.31597 3.49649 3.70833 3.33329 4.16667 3.33329H5V1.66663H6.66667V3.33329H13.3333V1.66663H15V3.33329H15.8333C16.2917 3.33329 16.684 3.49649 17.0104 3.82288C17.3368 4.14926 17.5 4.54163 17.5 4.99996V16.6666C17.5 17.125 17.3368 17.5173 17.0104 17.8437C16.684 18.1701 16.2917 18.3333 15.8333 18.3333H4.16667ZM4.16667 16.6666H15.8333V8.33329H4.16667V16.6666ZM4.16667 6.66663H15.8333V4.99996H4.16667V6.66663Z"/>
                                </svg>
                            </div>
                            <div class="articles-blog-body__value">
                                <time datetime="<?php echo esc_attr(get_the_date('c', $featured_article)); ?>"><?php echo esc_html($article_date); ?></time>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="articles-blog-body__decor">
                <div class="articles-blog-body__category">
                    <?php echo esc_html($article_category); ?>
                </div>
                <?php if ($featured_image_id) : ?>
                <div class="articles-blog-body__image">
                    <?php echo wp_get_attachment_image($featured_image_id, 'medium_large', false, [
                        'loading' => 'lazy',
                        'alt' => esc_attr($article_title)
                    ]); ?>
                </div>
                <?php endif; ?>
            </div>
        </article>
    </div>
</div>
<?php endif; ?>

<?php
// Partners Section
$partners_title = get_field('partners_title');
$partner_logos = get_field('partner_logos');

if (!empty($partners_title) || (!empty($partner_logos) && is_array($partner_logos))) :
?>
<section class='home-partners section' aria-labelledby='home-partners-title'>
    <div class='home-partners__container'>
        <?php if (!empty($partners_title)) : ?>
        <h2 class="home-partners__title title" id="home-partners-title">
            <?php echo esc_html($partners_title); ?>
        </h2>
        <?php endif; ?>
        
        <?php if (!empty($partner_logos) && is_array($partner_logos)) : ?>
        <div class="home-partners__items">
            <?php foreach ($partner_logos as $logo) : ?>
            <div class="home-partners__item">
                <?php echo wp_get_attachment_image($logo['ID'], 'medium', false, [
                    'alt' => esc_attr($logo['alt'] ?: 'Partner logo'),
                    'loading' => 'lazy'
                ]); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<section class="pricing-details section" aria-labelledby="pricing-details-title">
    <div class="pricing-details__container">
        <div class="pricing-details__body body">
            <h2 class="pricing-details__title title" id="pricing-details-title">
                <?php echo wp_kses_post(get_field('pricing_details_title') ?: 'What\'s Included<span>?</span>'); ?>
            </h2>
            <div class="pricing-details__subtitle">
                <p>
                    <?php echo wp_kses_post(get_field('pricing_details_text') ?: 'For one flat fee, you\'ll receive a detailed report based on your name and date of birth, referencing police databases to determine whether a criminal record exists. This is an official name-based check used across Canada for employment, volunteer work, and more.'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="get-started section get-started-mbn" aria-labelledby="get-started-title">
    <div class="get-started__container">
        <div class="get-started__body body">
            <h2 class="get-started__title title" id="get-started-title">
                <?php echo wp_kses_post(get_field('pricing_cta_title') ?: 'Get Started Today<span>!</span>'); ?>
            </h2>
            <div class="get-started__subtitle">
                <p>
                    <?php echo esc_html(get_field('pricing_cta_subtitle') ?: 'Skip the lines and get your official Canadian background check completed online—usually in minutes.'); ?>
                </p>
            </div>
            <?php 
            $cta_button = get_field('pricing_cta_button');
            
            // Use the CTA button template part
            get_template_part('template-parts/cta-button', null, array(
                'cta_button' => $cta_button,
                'class' => 'get-started__button button-red',
            ));
            ?>
        </div>
    </div>
</section>


<?php
// Include the centralized FAQ module
get_template_part('template-parts/faq-module');

get_footer();