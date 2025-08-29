<?php
/**
 * Template Name: Home Page
 *
 * @package SelfScan
 */

get_header();
?>

<section class="hero-home section" aria-labelledby="hero-home-title">
    <div class="hero-home__container">
        <div class="hero-home__body body">
            <div class="hero-home__info">
                <h1 class="hero-home__title title title-medium" id="hero-home-title">
                    <?php echo esc_html(get_field('hero_title') ?: 'Certified Criminal Background Checks Without Leaving Home'); ?>
                </h1>
                <div class="hero-home__subtitle">
                    <p>
                        <?php echo esc_html(get_field('hero_subtitle') ?: 'Get your certified Canadian criminal background check delivered to your inbox in minutes.'); ?>
                    </p>
                </div>
                <ul class="hero-home__list">
                    <?php 
                    $hero_points = get_field('hero_points');
                    if (!$hero_points) {
                        $hero_points = [
                            ['point' => 'Trusted by employers and organizations across Canada'],
                            ['point' => '100% online – no need to visit a police station'],
                            ['point' => 'Safe, secure, and RCMP-accredited process']
                        ];
                    }
                    
                    foreach ($hero_points as $point_data) : 
                        $point = isset($point_data['point']) ? $point_data['point'] : $point_data;
                    ?>
                    <li class="hero-home__item">
                        <div class="hero-home__icon">
                            <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/checkmark.svg', ['class' => 'hero-home__icon-svg']); ?>
                        </div>
                        <div class="hero-home__point">
                            <?php echo esc_html($point); ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php 
                $hero_button = get_field('hero_button_cta');
                
                // Use the CTA button template part instead of hardcoding
                get_template_part('template-parts/cta-button', null, array(
                    'cta_button' => $hero_button,
                    'class' => 'hero-home__button button-red',
                ));
                ?>
                <?php 
                $disclaimer = get_field('cta_disclaimer_text'); // Secure, trusted and SOC2 compliant
                if ($disclaimer) :
                ?>
                <span class="disclaimer"><?php echo wp_kses_post($disclaimer); ?></span>
                <?php endif; ?>
            </div>
            <div class="hero-home__decor">
                <?php 
                $hero_image_id = get_field('hero_image_id') ?: 18;
                echo wp_get_attachment_image($hero_image_id, 'full', false, ['loading' => 'eager', 'alt' => 'Hero Image']);
                ?>
            </div>
        </div>
    </div>
</section>

<?php
// Reviews/Testimonials Section
$testimonials = get_field('testimonials');
$reviews_title = get_field('reviews_title');
$reviews_subtitle = get_field('reviews_subtitle');

if ($testimonials && !empty($testimonials)) :
?>
<section class='reviews section' aria-labelledby='reviews-title'>
    <div class='reviews__container'>
        <div class="reviews__header">
            <h2 class="reviews__title title" id="reviews-title">
                <?php echo esc_html($reviews_title ?: 'Trusted by Thousands of Canadians'); ?>
            </h2>
            <div class="reviews__subtitle">
                <?php echo esc_html($reviews_subtitle ?: 'See what our customers are saying about their experience'); ?>
            </div>
        </div>
        <div class="reviews__body">
            <div class='reviews__swiper swiper'>
                <div class='reviews__wrapper swiper-wrapper'>
                    <?php foreach ($testimonials as $testimonial) : 
                        $media_type = $testimonial['media_type'] ?? 'image';
                        $is_video = ($media_type === 'video');
                        $author_name = $testimonial['author_name'] ?? '';
                        $author_job_title = $testimonial['author_job_title'] ?? '';
                        $author_location = $testimonial['author_location'] ?? '';
                        $testimonial_text = $testimonial['testimonial_text'] ?? '';
                    ?>
                    <div class='reviews__slide swiper-slide'>
                        <div class="reviews__card">
                            <div class="reviews__rating">
                                <img src="<?php echo get_template_directory_uri(); ?>/img/main/rating.svg" loading="lazy" alt="rating">
                            </div>
                            <div class="reviews__text">
                                <p><?php echo esc_html($testimonial_text); ?></p>
                            </div>
                            <div class="reviews__info">
                                <?php if (!$is_video && !empty($testimonial['author_image'])) : ?>
                                <div class="reviews__avatar">
                                    <?php echo wp_get_attachment_image($testimonial['author_image'], 'thumbnail', false, ['alt' => esc_attr($author_name)]); ?>
                                </div>
                                <?php endif; ?>
                                <div class="reviews__details">
                                    <div class="reviews__name">
                                        <?php echo esc_html($author_name); ?>
                                    </div>
                                    <div class="reviews__data<?php echo $is_video ? ' reviews__data-alt' : ''; ?>">
                                        <div class="reviews__position">
                                            <?php echo esc_html($author_job_title); ?><?php echo $is_video ? ',' : ''; ?>
                                        </div>
                                        <div class="reviews__location">
                                            <?php echo esc_html($author_location); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($is_video) : ?>
                            <div class="reviews__video">
                                <?php 
                                $video_file = $testimonial['author_video'] ?? null;
                                $video_url = $testimonial['author_video_url'] ?? '';
                                
                                if ($video_file && !empty($video_file)) {
                                    $video_src = $video_file['url'] ?? '';
                                    if ($video_src) {
                                        echo '<video data-video-player playsinline controls>';
                                        echo '<source src="' . esc_url($video_src) . '" type="' . esc_attr($video_file['mime_type'] ?? 'video/mp4') . '" />';
                                        echo '</video>';
                                    }
                                } elseif ($video_url) {
                                    // Handle different video types (YouTube, Vimeo, direct links)
                                    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                        // YouTube embed
                                        $video_id = '';
                                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                                            $video_id = $matches[1];
                                        }
                                        if ($video_id) {
                                            echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allowfullscreen></iframe>';
                                        }
                                    } elseif (strpos($video_url, 'vimeo.com') !== false) {
                                        // Vimeo embed
                                        $video_id = '';
                                        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|)(\d+)(?:$|\/|\?)/', $video_url, $matches)) {
                                            $video_id = $matches[1];
                                        }
                                        if ($video_id) {
                                            echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '" frameborder="0" allowfullscreen></iframe>';
                                        }
                                    } else {
                                        // Direct video link
                                        echo '<video data-video-player playsinline controls>';
                                        echo '<source src="' . esc_url($video_url) . '" type="video/mp4" />';
                                        echo '</video>';
                                    }
                                }
                                ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="about-home section" aria-labelledby="about-home-title">
    <div class="about-home__container">
        <div class="about-home__body body">
            <h2 class="about-home__title title" id="about-home-title">
                <?php echo wp_kses_post(get_field('about_title') ?: 'What is a Self Scan'); ?>
            </h2>
            <div class="about-home__subtitle">
                <?php echo esc_html(get_field('about_subtitle') ?: 'Official Name-Based RCMP Criminal Record Check'); ?>
            </div>
            <div class="about-home__content">
                <div class="about-home__info">
                    <div class="about-home__label">
                        <?php echo esc_html(get_field('about_label') ?: 'SelfScan.ca partners with police detachments across Canada.'); ?>
                    </div>
                    <div class="about-home__text">
                        <?php echo wp_kses_post(get_field('about_text') ?: '<p>A Criminal Record Check is a secure, government-authorized screening that shows whether an individual has a criminal history. It\'s often required for employment, volunteering, or licensing. With SelfScan, you can quickly and confidentially request your official Canadian criminal record check online—anywhere, anytime.</p>'); ?>
                    </div>
                    <div class="about-home__stats">
                        <?php
                        $key_points = get_field('about_key_points');
                        if (!$key_points) {
                            // Fallback stats if no ACF data
                            ?>
                            <dl class="about-home__item">
                                <dt class="about-home__value">
                                    <?php echo esc_html('$59.99'); ?>
                                </dt>
                                <dd class="about-home__description">
                                    <?php echo esc_html('Name-Based RCMP Criminal Record Check'); ?>
                                </dd>
                            </dl>
                            <dl class="about-home__item">
                                <dt class="about-home__value">
                                    <?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/clock.svg', ['class' => 'about-home__icon']); ?>
                                </dt>
                                <dd class="about-home__description">
                                    <?php echo esc_html('Get Results Within the Hour'); ?>
                                </dd>
                            </dl>
                            <?php
                        } else {
                            // Loop through ACF key points
                            foreach ($key_points as $point) :
                                $title = isset($point['title']) ? $point['title'] : '';
                                $key_point = isset($point['key_point']) ? $point['key_point'] : [];
                                
                                if (!empty($key_point)) :
                                    ?>
                                    <dl class="about-home__item">
                                        <dt class="about-home__value">
                                            <?php 
                                            // Check if this is a text or image key point
                                            if (isset($key_point[0]['acf_fc_layout']) && $key_point[0]['acf_fc_layout'] == 'text_key_point') {
                                                echo esc_html($key_point[0]['text']);
                                            } elseif (isset($key_point[0]['acf_fc_layout']) && $key_point[0]['acf_fc_layout'] == 'image_key_point') {
                                                $image_id = $key_point[0]['image'];
                                                if ($image_id) {
                                                    // Get the image URL to check if it's an SVG
                                                    $image_url = wp_get_attachment_url($image_id);
                                                    
                                                    if ($image_url && pathinfo($image_url, PATHINFO_EXTENSION) === 'svg') {
                                                        // It's an SVG, use inline_svg function
                                                        selfscan_inline_svg($image_url, ['class' => 'about-home__icon']);
                                                    } else {
                                                        // Not an SVG, use the normal image tag
                                                        echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'about-home__icon']);
                                                    }
                                                } else {
                                                    selfscan_inline_svg(get_template_directory_uri() . '/img/icons/clock.svg', ['class' => 'about-home__icon']);
                                                }
                                            }
                                            ?>
                                        </dt>
                                        <dd class="about-home__description">
                                            <?php echo esc_html($title); ?>
                                        </dd>
                                    </dl>
                                    <?php
                                endif;
                            endforeach;
                        }
                        ?>
                    </div>
                </div>
                <div class="about-home__decor">
                    <?php 
                    $about_image_id = get_field('about_image_id') ?: 21;
                    echo wp_get_attachment_image($about_image_id, 'full', false, ['loading' => 'lazy', 'alt' => 'Map']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="how-works section" aria-labelledby="how-works-title">
    <div class="how-works__container">
        <div class="how-works__body body">
            <h2 class="how-works__title title" id="how-works-title">
                <?php echo wp_kses_post(get_field('how_works_title') ?: 'How It Works<span>.</span>'); ?>
            </h2>
            <div class="how-works__items">
                <?php
                $steps = get_field('how_works_steps');
                if (!$steps) {
                    $steps = [
                        [
                            'img_id' => 17,
                            'title' => 'Complete Quick Form',
                            'desc' => 'Fill out a short online form with your basic info and consent.'
                        ],
                        [
                            'img_id' => 20,
                            'title' => 'Verify Your Identity',
                            'desc' => 'Securely confirm your identity through our trusted verification process.'
                        ],
                        [
                            'img_id' => 16,
                            'title' => 'Get Your Report',
                            'desc' => 'Sit back and relax. Most background checks are emailed within 15 minutes'
                        ]
                    ];
                }
                
                foreach ($steps as $step) : ?>
                <article class="how-works__item">
                    <div class="how-works__image">
                        <?php 
                        $step_image_id = isset($step['img_id']) ? $step['img_id'] : 0;
                        if ($step_image_id) {
                            echo wp_get_attachment_image($step_image_id, 'full', false, [
                                'loading' => 'lazy', 
                                'alt' => esc_attr($step['title']),
                                'class' => 'how-works__img'
                            ]);
                        } else {
                            // Fallback to URL if image ID is not available
                            echo '<img src="' . esc_url($step['image']) . '" loading="lazy" alt="' . esc_attr($step['title']) . '">';
                        }
                        ?>
                    </div>
                    <div class="how-works__info">
                        <h3 class="how-works__name">
                            <?php echo esc_html($step['title']); ?>
                        </h3>
                    </div>
                    <div class="how-works__details">
                        <p>
                            <?php echo wp_kses_post($step['desc']); ?>
                        </p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="why-we section" aria-labelledby="why-we-title">
    <div class="why-we__container">
        <div class="why-we__body body">
            <h2 class="why-we__title title" id="why-we-title">
                <?php echo wp_kses_post(get_field('why_choose_title') ?: 'Why Choose Us<span>.</span>'); ?>
            </h2>
            <div class="why-we__items">
                <?php
                $reasons = get_field('why_choose_reasons');
                if (!$reasons) {
                    $reasons = [
                        [
                            'icon' => 'why-we__icon_01',
                            'title' => 'Fast Results',
                            'desc' => 'Most checks returned within minutes — no waiting, no in-person visits.'
                        ],
                        [
                            'icon' => 'why-we__icon_02',
                            'title' => 'RCMP-Accredited',
                            'desc' => 'We partner with Canadian police agencies to provide certified results.'
                        ],
                        [
                            'icon' => 'why-we__icon_03',
                            'title' => 'Fully Online',
                            'desc' => 'Do it all from your phone, tablet, or computer.'
                        ],
                        [
                            'icon' => 'why-we__icon_04',
                            'title' => 'Privacy First',
                            'desc' => 'Your personal information is protected under federal and provincial privacy laws.'
                        ]
                    ];
                }
                
                foreach ($reasons as $reason) : ?>
                <article class="why-we__item">
                    <div class="why-we__icon">
                        <?php
                        // Check if the icon is a media library ID
                        if (isset($reason['icon']) && is_numeric($reason['icon'])) {
                            // It's a media library ID, use it directly
                            $attachment_id = $reason['icon'];
                            $attachment_url = wp_get_attachment_url($attachment_id);
                            
                            if ($attachment_url) {
                                // If it's an SVG, use inline_svg function
                                if (pathinfo($attachment_url, PATHINFO_EXTENSION) === 'svg') {
                                    selfscan_inline_svg($attachment_url, ['class' => 'why-we__icon-svg']);
                                } else {
                                    // For other image types, use the wp_get_attachment_image function
                                    echo wp_get_attachment_image($attachment_id, 'thumbnail', false, ['class' => 'why-we__icon-svg']);
                                }
                            }
                        } else {
                            // Fallback for demo data or if icon is not a media ID
                            $icon_name = '';
                            switch($reason['icon']) {
                                case 'why-we__icon_01':
                                    $icon_name = 'why-icon-1';
                                    break;
                                case 'why-we__icon_02':
                                    $icon_name = 'why-icon-2';
                                    break;
                                case 'why-we__icon_03':
                                    $icon_name = 'why-icon-3';
                                    break;
                                case 'why-we__icon_04':
                                    $icon_name = 'why-icon-4';
                                    break;
                                default:
                                    $icon_name = 'why-icon-1';
                            }
                            selfscan_inline_svg(get_template_directory_uri() . '/img/icons/' . $icon_name . '.svg', ['class' => 'why-we__icon-svg']);
                        }
                        ?>
                    </div>
                    <div class="why-we__info">
                        <h3 class="why-we__label">
                            <?php echo esc_html($reason['title']); ?>
                        </h3>
                        <div class="why-we__text">
                            <?php echo esc_html($reason['desc']); ?>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="for-who section" aria-labelledby="for-who-title">
    <div class="for-who__container">
        <div class="for-who__body body">
            <h2 class="for-who__title title" id="for-who-title">
                <?php echo wp_kses_post(get_field('for_who_title') ?: 'Who Is This For<span>.</span>'); ?>
            </h2>
            <div class="for-who__content">
                <div class="for-who__info">
                    <h3 class="for-who__label">
                        <?php echo esc_html(get_field('for_who_label') ?: 'Our Online Criminal record check is perfect for'); ?>
                    </h3>
                    <div class="for-who__items">
                        <?php
                        $items = get_field('for_who_items');
                        if (!$items) {
                            $items = [
                                ['item' => 'Employment applications'],
                                ['item' => 'Volunteer work'],
                                ['item' => 'Rental agreements'],
                                ['item' => 'Education programs'],
                                ['item' => 'Professional licensing'],
                                ['item' => 'Many more...']
                            ];
                        }
                        
                        foreach ($items as $item_data) : 
                            $item = isset($item_data['item']) ? $item_data['item'] : $item_data;
                        ?>
                        <div class="for-who__item">
                            <?php echo esc_html($item); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="for-who__disclaimer">
                        <?php echo wp_kses_post(get_field('for_who_disclaimer') ?: 'Note: <span>Vulnerable Sector Checks</span> (for working with children, seniors, etc.) are not available through this service and <span>must be conduct at your local police station.</span>'); ?>
                    </div>
                </div>
                <div class="for-who__decor">
                    <?php 
                    $for_who_image_id = get_field('for_who_image_id') ?: 19;
                    echo wp_get_attachment_image($for_who_image_id, 'full', false, ['loading' => 'lazy', 'alt' => 'For who image']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include the centralized FAQ module
get_template_part('template-parts/faq-module');
?>

<section class="get-started section" aria-labelledby="get-started-title">
    <div class="get-started__container">
        <div class="get-started__body body">
            <h2 class="get-started__title title" id="get-started-title">
                <?php echo esc_html(get_field('cta_title') ?: 'Get Started in 3 Minutes'); ?><span>!</span>
            </h2>
            <div class="get-started__subtitle">
                <p>
                    <?php echo esc_html(get_field('cta_subtitle') ?: 'Skip the line and get the background check you need — fast, easy, and secure.'); ?>
                </p>
            </div>
            <?php 
            $cta_button = get_field('cta_button_url');  
            
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