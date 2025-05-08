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

<section class="faq-contact section" aria-labelledby="faq-contact-title">
    <div class="faq-contact__container">
        <div class="faq-contact__body body">
            <h2 class="faq-contact__title title" id="faq-contact-title">
                <?php echo wp_kses_post(get_field('faq_contact_title') ?: 'Still Have Questions<span>?</span>'); ?>
            </h2>
            <div class="faq-contact__content">
                <div class="faq-contact__info">
                    <p class="faq-contact__text">
                        <?php echo esc_html(get_field('faq_contact_text') ?: 'Our support team is here to help you.'); ?>
                    </p>
                    <div class="faq-contact__methods">
                        <?php
                        $contact_methods = get_field('faq_contact_methods');
                        if (!$contact_methods) {
                            // Fallback: Show default email contact method
                            ?>
                            <a href="mailto:<?php echo esc_attr(get_field('faq_contact_email') ?: 'info@selfscan.ca'); ?>" class="faq-contact__method">
                                <div class="faq-contact__icon">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM19.6 8.25L12.53 12.67C12.21 12.87 11.79 12.87 11.47 12.67L4.4 8.25C4.15 8.09 4 7.82 4 7.53C4 6.86 4.73 6.46 5.3 6.81L12 11L18.7 6.81C19.27 6.46 20 6.86 20 7.53C20 7.82 19.85 8.09 19.6 8.25Z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <div class="faq-contact__details">
                                    <h3><?php echo esc_html(get_field('faq_contact_email_label') ?: 'Email Support'); ?></h3>
                                    <p><?php echo esc_html(get_field('faq_contact_email') ?: 'info@selfscan.ca'); ?></p>
                                </div>
                            </a>
                            <?php
                        } else {
                            // Loop through contact methods from ACF
                            foreach ($contact_methods as $method) :
                                $contact_type = isset($method['type']) ? $method['type'] : 'Email Support';
                                $contact_info = isset($method['info']) ? $method['info'] : 'info@selfscan.ca';
                                $icon_id = isset($method['icon']) ? $method['icon'] : 0;
                                
                                // Determine if it's an email or phone
                                $is_email = (strpos(strtolower($contact_type), 'email') !== false) || (strpos($contact_info, '@') !== false);
                                $href = $is_email ? "mailto:{$contact_info}" : "tel:" . preg_replace('/[^0-9+]/', '', $contact_info);
                                ?>
                                <a href="<?php echo esc_attr($href); ?>" class="faq-contact__method">
                                    <div class="faq-contact__icon">
                                        <?php 
                                        if ($icon_id) {
                                            // Display the custom icon from media library
                                            echo wp_get_attachment_image($icon_id, 'thumbnail', false, ['class' => 'faq-contact__icon-img']);
                                        } else {
                                            // Default email icon fallback
                                            ?>
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM19.6 8.25L12.53 12.67C12.21 12.87 11.79 12.87 11.47 12.67L4.4 8.25C4.15 8.09 4 7.82 4 7.53C4 6.86 4.73 6.46 5.3 6.81L12 11L18.7 6.81C19.27 6.46 20 6.86 20 7.53C20 7.82 19.85 8.09 19.6 8.25Z" fill="currentColor"/>
                                            </svg>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="faq-contact__details">
                                        <h3><?php echo esc_html($contact_type); ?></h3>
                                        <p><?php echo esc_html($contact_info); ?></p>
                                    </div>
                                </a>
                                <?php
                            endforeach;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
            $button_text = isset($cta_button['title']) ? $cta_button['title'] : (get_field('faq_cta_button_text') ?: 'Start Background Check');
            $button_url = isset($cta_button['url']) ? $cta_button['url'] : (get_field('faq_cta_button_url') ?: '#');
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
get_footer(); 