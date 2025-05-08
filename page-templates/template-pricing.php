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
            $hero_button = get_field('pricing_button');
            $button_text = isset($hero_button['title']) ? $hero_button['title'] : 'Get Started';
            $button_url = isset($hero_button['url']) ? $hero_button['url'] : '#';
            ?>
            <a href="<?php echo esc_url($button_url); ?>" class="pricing-hero__button button button-red">
                <span class="button__text">
                    <?php echo esc_html($button_text); ?>
                </span>
                <span class="button__icon">
                    <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-right.svg', ['class' => 'button__icon-svg']); ?>
                </span>
            </a>
        </div>
    </div>
</section>

<div class="pricing-reviews section">
    <div class="pricing-reviews__container">
        <div class="pricing-reviews__body">
            <?php
            // Get reviews from custom field or use defaults
            $reviews = get_field('pricing_reviews');
            if (!$reviews) {
                $reviews = [
                    [
                        'text' => 'I recently used selfscan.ca for my Canadian background check and was very pleased! The process was easy, and I got my results quickly, helping me secure my new job. I highly recommend selfscan.ca for anyone needing a reliable background check service in Canada.',
                        'name' => 'Fannie Huel',
                        'position' => 'Financial Controller',
                        'avatar_id' => 22,
                        'company_logo_id' => 12 // Stripe Logo ID.
                    ],
                    [
                        'text' => 'I used selfscan.ca for my background check in Canada, and it was great! The process was quick, and I got my results fast, helping me secure my new job. I highly recommend selfscan.ca for reliable background checks!',
                        'name' => 'John Smith',
                        'position' => 'Bank Manager',
                        'avatar_id' => 23,
                        'company_logo_id' => 11 // NBC Logo ID.
                    ],
                    [
                        'text' => 'I recently used selfscan.ca for my Canadian background check and was very pleased! The process was easy, and I got my results quickly, helping me secure my new job. I highly recommend selfscan.ca for anyone needing a reliable background check service in Canada.',
                        'name' => 'Fannie Huel',
                        'position' => 'Financial Controller',
                        'avatar_id' => 22,
                        'company_logo_id' => 12 // Stripe Logo ID.
                    ],
                    [
                        'text' => 'I used selfscan.ca for my background check in Canada, and it was great! The process was quick, and I got my results fast, helping me secure my new job. I highly recommend selfscan.ca for reliable background checks!',
                        'name' => 'John Smith',
                        'position' => 'Bank Manager',
                        'avatar_id' => 23,
                        'company_logo_id' => 11 // NBC Logo ID.
                    ]
                ];
            }
            
            $review_count = count($reviews);
            $enable_swiper = $review_count >= 3;
            ?>
            
            <?php if ($enable_swiper): ?>
            <div class="pricing-reviews__swiper swiper" data-enable-swiper="true">
                <div class="pricing-reviews__wrapper swiper-wrapper">
            <?php else: ?>
            <div class="pricing-reviews__static">
                <div class="pricing-reviews__static-wrapper">
            <?php endif; ?>
                
                <?php foreach ($reviews as $review): ?>
                    <div class="<?php echo $enable_swiper ? 'pricing-reviews__slide swiper-slide' : 'pricing-reviews__item'; ?>">
                        <div class="pricing-reviews__content">
                            <div class="pricing-reviews__text">
                                <?php echo esc_html($review['text']); ?>
                            </div>
                            <div class="pricing-reviews__footer">
                                <div class="pricing-reviews__info">
                                    <div class="pricing-reviews__avatar">
                                        <?php 
                                        $avatar_id = isset($review['avatar_id']) ? $review['avatar_id'] : 0;
                                        if ($avatar_id) {
                                            echo wp_get_attachment_image($avatar_id, 'thumbnail', false, [
                                                'loading' => 'lazy',
                                                'alt' => esc_attr($review['name'])
                                            ]);
                                        }
                                        ?>
                                    </div>
                                    <div class="pricing-reviews__details">
                                        <div class="pricing-reviews__name">
                                            <?php echo esc_html($review['name']); ?>
                                        </div>
                                        <div class="pricing-reviews__position">
                                            <?php echo esc_html($review['position']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="pricing-reviews__company">
                                    <?php 
                                    $company_logo_id = isset($review['company_logo_id']) ? $review['company_logo_id'] : 0;
                                    if ($company_logo_id) {
                                        echo wp_get_attachment_image($company_logo_id, 'medium', false, [
                                            'loading' => 'lazy',
                                            'alt' => 'Company logo'
                                        ]);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            
            <?php if ($enable_swiper): ?>
            <div class="pricing-reviews__nav">
                <button type="button" aria-label="previous-slide" class="pricing-reviews__button pricing-reviews__button-prev">
                    <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-right.svg', ['class' => 'pricing-reviews__button-icon']); ?>
                </button>
                <button type="button" aria-label="next-slide" class="pricing-reviews__button pricing-reviews__button-next">
                    <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-right.svg', ['class' => 'pricing-reviews__button-icon']); ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

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
            $button_text = isset($cta_button['title']) ? $cta_button['title'] : 'Start Background Check';
            $button_url = isset($cta_button['url']) ? $cta_button['url'] : '#';
            ?>
            <a href="<?php echo esc_url($button_url); ?>" class="get-started__button button button-red">
                <span class="button__text">
                    <?php echo esc_html($button_text); ?>
                </span>
                <span class="button__icon">
                    <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-right.svg', ['class' => 'button__icon-svg']); ?>
                </span>
            </a>
        </div>
    </div>
</section>

<?php
// Include the centralized FAQ module
get_template_part('template-parts/faq-module');

get_footer();