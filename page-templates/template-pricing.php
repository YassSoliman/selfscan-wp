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
// Include the centralized FAQ module
get_template_part('template-parts/faq-module');

get_footer();