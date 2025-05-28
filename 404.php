<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package selfscan
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="hero-home section" aria-labelledby="error-404-title" style="margin-bottom: 1.25rem;">
        <div class="hero-home__container">
            <div class="hero-home__body body">
                <div class="hero-home__info" style="flex: 1 1 100%;">
                    <h1 class="hero-home__title title title-medium" id="error-404-title">
                        <?php esc_html_e('404 - Page Not Found', 'selfscan'); ?>
                    </h1>
                    <div class="hero-home__subtitle">
                        <p>
                            <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'selfscan'); ?>
                        </p>
                    </div>
                    <?php 
                    // Use the CTA button template part for the back to home button
                    get_template_part('template-parts/cta-button', null, array(
                        'cta_button' => array(
                            'url' => home_url('/'),
                            'title' => __('Back to Home', 'selfscan'),
                            'target' => '_self'
                        ),
                        'class' => 'button-red',
                    ));
                    ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
