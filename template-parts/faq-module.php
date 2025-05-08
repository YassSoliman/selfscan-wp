<?php
/**
 * Template part for displaying the FAQ module
 *
 * @package SelfScan
 */

// Get FAQ section title
$faq_title = get_field('faq_section_title') ?: 'Frequently Asked Questions';

// Determine which FAQs to display
$selected_faqs = get_field('selected_faqs');
$max_faqs = get_field('max_faqs');

// Query arguments
$args = array(
    'post_type' => 'faq',
    'posts_per_page' => $max_faqs ? intval($max_faqs) : -1,
    'orderby' => 'menu_order title',
    'order' => 'ASC',
);

// If specific FAQs are selected
if (!empty($selected_faqs)) {
    $args['post__in'] = $selected_faqs;
    $args['orderby'] = 'post__in';
}

$faq_query = new WP_Query($args);

// Build unique ID for this module instance
$faq_section_id = 'faq-section-' . uniqid();
?>

<section class="faq section" aria-labelledby="<?php echo esc_attr($faq_section_id); ?>">
    <div class="faq__container">
        <div class="faq__body body">
            <h2 class="faq__title title" id="<?php echo esc_attr($faq_section_id); ?>">
                <?php echo wp_kses_post($faq_title); ?>
            </h2>

            <div class="faq__content content-faq" data-spollers data-one-spoller>
                <?php
                if ($faq_query->have_posts()) :
                    while ($faq_query->have_posts()) : $faq_query->the_post();
                        $question = get_the_title();
                        $answer = get_field('faq_description');
                        ?>
                        <div class="content-faq__item">
                            <button tabindex="-1" type="button" data-spoller class="content-faq__button">
                                <span class="content-faq__label"><?php echo esc_html($question); ?></span>
                                <span class="content-faq__icon">
                                    <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-down.svg', ['class' => 'content-faq__icon-svg', 'width' => '50', 'height' => '51']); ?>
                                </span>
                            </button>
                            <div class="content-faq__body">
                                <?php echo $answer; ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback content if no FAQs are found
                    ?>
                    <div class="content-faq__item">
                        <button tabindex="-1" type="button" data-spoller class="content-faq__button">
                            <span class="content-faq__label">No FAQs Found</span>
                            <span class="content-faq__icon">
                                <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/arrow-down.svg', ['class' => 'content-faq__icon-svg', 'width' => '50', 'height' => '51']); ?>
                            </span>
                        </button>
                        <div class="content-faq__body">
                            <p>Please add FAQs via the WordPress admin or select FAQs for this section.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section> 