<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package selfscan
 */

?>

        </main>
        <footer class="footer section">
            <div class="footer__container">
                <div class="footer__body body">
                    <div class="footer__content">
                        <div class="footer__info">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="footer__logo" aria-label="link homepage">
                                <img width="213" height="45" loading="lazy" src="<?php echo esc_url(get_theme_mod('selfscan_logo_light', get_template_directory_uri() . '/img/main/logo-white.svg')); ?>" alt="<?php bloginfo('name'); ?>">
                            </a>
                            <div class="footer__text">
                                <p>
                                    <?php 
                                    $default_text = 'SelfScan.ca is the easiest, quickest, and most affordable way for Canadians to
                                    obtain their Name-Based RCMP Criminal
                                    Record Check. Whether it\'s required for employment, school, or volunteering,
                                    don\'t waste your time standing in line and
                                    filling out paperwork at the police station or post office, obtain your official
                                    signed police certificate within the
                                    comfort of your home.';
                                    
                                    if (function_exists('selfscan_get_multilingual_option')) {
                                        echo wp_kses_post(selfscan_get_multilingual_option('footer_text', $default_text));
                                    } else {
                                        echo wp_kses_post(get_theme_mod('footer_text', $default_text));
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="footer__partners">
                                <?php 
                                // Get footer partners data
                                $partners_data = get_theme_mod('footer_partner_1', '');
                                $partners = array();
                                
                                // Parse the JSON data
                                if (!empty($partners_data)) {
                                    $partners = json_decode($partners_data, true);
                                }
                                
                                // Check for legacy data (backwards compatibility)
                                if (empty($partners)) {
                                    // Check for old individual partner settings
                                    $legacy_partner_1 = get_theme_mod('footer_partner_1_legacy', 134);
                                    $legacy_partner_2 = get_theme_mod('footer_partner_2', 133);
                                    
                                    if ($legacy_partner_1 && !is_array($legacy_partner_1)) {
                                        $partners[] = array(
                                            'image_id' => $legacy_partner_1,
                                            'width' => '',
                                            'height' => ''
                                        );
                                    }
                                    
                                    if ($legacy_partner_2) {
                                        $partners[] = array(
                                            'image_id' => $legacy_partner_2,
                                            'width' => '',
                                            'height' => ''
                                        );
                                    }
                                }
                                
                                // Display partners
                                if (!empty($partners) && is_array($partners)) {
                                    foreach ($partners as $index => $partner) {
                                        if (!empty($partner['image_id']) && wp_get_attachment_url($partner['image_id'])) {
                                            $img_attrs = array(
                                                'loading' => 'lazy',
                                                'alt' => 'partner logo',
                                                'data-image-id' => $partner['image_id']
                                            );
                                            
                                            // Build container styles for width and height
                                            $container_style_parts = array();
                                            
                                            if (!empty($partner['width'])) {
                                                $width_unit = isset($partner['width_unit']) ? $partner['width_unit'] : 'rem';
                                                if ($width_unit === 'auto') {
                                                    $container_style_parts[] = 'width: auto';
                                                } else {
                                                    $width_value = floatval($partner['width']);
                                                    $container_style_parts[] = 'width: ' . $width_value . $width_unit;
                                                }
                                            }
                                            
                                            if (!empty($partner['height'])) {
                                                $height_unit = isset($partner['height_unit']) ? $partner['height_unit'] : 'rem';
                                                if ($height_unit === 'auto') {
                                                    $container_style_parts[] = 'height: auto';
                                                } else {
                                                    $height_value = floatval($partner['height']);
                                                    $container_style_parts[] = 'height: ' . $height_value . $height_unit;
                                                }
                                            }
                                            
                                            $container_style = '';
                                            if (!empty($container_style_parts)) {
                                                $container_style = ' style="' . esc_attr(implode('; ', $container_style_parts) . ';') . '"';
                                            }
                                            
                                            echo '<div class="footer__partner" data-partner-index="' . esc_attr($index) . '"' . $container_style . '>';
                                            echo wp_get_attachment_image($partner['image_id'], 'full', false, $img_attrs);
                                            echo '</div>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="footer__action">
                            <?php
                            // Display footer right menu
                            if (has_nav_menu('footer-right')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'footer-right',
                                    'container'      => false,
                                    'items_wrap'     => '%3$s',
                                    'walker'         => new SelfScan_Walker_Footer_Right_Menu(),
                                    'fallback_cb'    => false,
                                ));
                            } ?>
                        </div>
                    </div>
                    <div class="footer__bottom">
                        <div class="footer__copy">
                            <?php 
                            $default_copyright = '&copy; ' . get_bloginfo('name') . ' ' . date('Y');
                            
                            if (function_exists('selfscan_get_multilingual_option')) {
                                echo wp_kses_post(selfscan_get_multilingual_option('copyright_text', $default_copyright));
                            } else {
                                echo wp_kses_post(get_theme_mod('copyright_text', $default_copyright));
                            }
                            ?>
                        </div>
                        <nav class='footer-menu'>
                            <?php
                            // Display footer menu
                            if (has_nav_menu('footer-menu')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'footer-menu',
                                    'container'      => false,
                                    'menu_class'     => 'footer-menu__list',
                                    'walker'         => new SelfScan_Walker_Footer_Menu(),
                                    'fallback_cb'    => false,
                                ));
                            } ?>
                        </nav>
                    </div>
                </div>
            </div>
        </footer>
    </div><!-- .wrapper -->
    <?php wp_footer(); ?>
</body>
</html>
