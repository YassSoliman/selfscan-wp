<?php
/**
 * Template Name: FAQ Page
 *
 * @package SelfScan
 */

get_header();

// Include the centralized FAQ module
get_template_part('template-parts/faq-module');
?>

<section class="get-started section" aria-labelledby="get-started-title">
    <div class="get-started__container">
        <div class="get-started__body body">
            <h2 class="get-started__title title" id="get-started-title">
                <?php echo wp_kses_post(get_field('faq_cta_title') ?: 'Ready to Get Started<span>?</span>'); ?>
            </h2>
            <div class="get-started__subtitle">
                <p>
                    <?php echo esc_html(get_field('faq_cta_subtitle') ?: 'Skip the lines and get your official Canadian background check completed online—usually in minutes.'); ?>
                </p>
            </div>
            <?php 
            $cta_button = get_field('faq_cta_button');
            
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
get_footer(); 