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
<br />
<br />
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
                <a href="<?php echo esc_url($article_link); ?>" class="articles-blog-body__link">
                    <h3 class="articles-blog-body__label">
                        <?php echo esc_html($article_title); ?>
                    </h3>
                </a>
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
                                <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/autor-icon.svg', ['aria-hidden' => 'true']); ?>
                            </div>
                            <div class="articles-blog-body__value">
                                <?php echo esc_html($article_author); ?>
                            </div>
                        </div>
                        <div class="articles-blog-body__item" data-article-date-published>
                            <div class="articles-blog-body__icon">
                                <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/date-icon.svg', ['aria-hidden' => 'true']); ?>
                            </div>
                            <div class="articles-blog-body__value">
                                <time datetime="<?php echo esc_attr(get_the_date('c', $featured_article)); ?>"><?php echo esc_html($article_date); ?></time>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="articles-blog-body__decor">
                <a href="<?php echo esc_url($article_link); ?>" class="articles-blog-body__category">
                    <?php echo esc_html($article_category); ?>
                </a>
                <?php if ($featured_image_id) : ?>
                <a href="<?php echo esc_url($article_link); ?>" class="articles-blog-body__image">
                    <?php echo wp_get_attachment_image($featured_image_id, 'medium_large', false, [
                        'loading' => 'lazy',
                        'alt' => esc_attr($article_title)
                    ]); ?>
                </a>
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

<?php
// Include the centralized FAQ module
get_template_part('template-parts/faq-module');

get_footer();