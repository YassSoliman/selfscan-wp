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
                                    <?php echo wp_kses_post(get_theme_mod('footer_text', 'SelfScan.ca is the easiest, quickest, and most affordable way for Canadians to
                                    obtain their Name-Based RCMP Criminal
                                    Record Check. Whether it\'s required for employment, school, or volunteering,
                                    don\'t waste your time standing in line and
                                    filling out paperwork at the police station or post office, obtain your official
                                    signed police certificate within the
                                    comfort of your home.')); ?>
                                </p>
                            </div>
                            <div class="footer__partners">
                                <?php 
                                // Partner 1
                                $partner1_id = get_theme_mod('footer_partner_1', 134);
                                if ($partner1_id) : ?>
                                <div class="footer__partner">
                                    <?php echo wp_get_attachment_image($partner1_id, 'full', false, ['loading' => 'lazy', 'alt' => 'partner logo']); ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php 
                                // Partner 2
                                $partner2_id = get_theme_mod('footer_partner_2', 133);
                                if ($partner2_id) : ?>
                                <div class="footer__partner">
                                    <?php echo wp_get_attachment_image($partner2_id, 'full', false, ['loading' => 'lazy', 'alt' => 'partner logo']); ?>
                                </div>
                                <?php endif; ?>
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
                            <?php echo wp_kses_post(get_theme_mod('copyright_text', '&copy; ' . get_bloginfo('name') . ' ' . date('Y'))); ?>
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
