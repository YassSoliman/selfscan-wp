<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package selfscan
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<div class="wrapper">
		<?php
		/**
		 * Top Bar Section
		 * Only displays if enabled in theme options
		 */
		$show_top_bar = get_field('show_top_bar', 'option');
		$top_bar_content = get_field('top_bar_content', 'option');
		
		if ($show_top_bar && !empty($top_bar_content)) : ?>
		<div class="top-section section">
			<div class="top-section__info">
				<div class="top-section__container">
					<?php echo wp_kses_post($top_bar_content); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<!-- Mobile Menu Overlay -->
		<div class="mobile-menu-overlay"></div>

		<!-- Header -->
		<div class="header__wrapper">
			<header class="header section" data-sticky-element>
				<div class="header__container">
					<div class="header__body">
						<!-- Logo -->
						<div class="header__content">
							<a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo" aria-label="link homepage">
								<img width="213" height="45" src="<?php echo esc_url(get_theme_mod('selfscan_logo_dark', get_template_directory_uri() . '/img/main/logo-black.svg')); ?>" alt="<?php bloginfo('name'); ?>">
							</a>
						</div>
						
						<!-- Desktop Navigation -->
						<nav class="desktop-nav">
							<ul class="desktop-nav__list">
								<?php
								// Display primary menu
								if (has_nav_menu('primary')) {
									wp_nav_menu(array(
										'theme_location' => 'primary',
										'container'      => false,
										'items_wrap'     => '%3$s',
										'walker'         => new SelfScan_Walker_Nav_Menu(),
										'fallback_cb'    => false,
									));
								} else {
									// Fallback to static menu if no menu is set
									?>
									<li class="menu-header__item">
										<a href="<?php echo esc_url(home_url('/pricing')); ?>" class="menu-header__link<?php if (is_page('pricing')) echo ' _active'; ?>">Pricing</a>
									</li>
									<li class="menu-header__item">
										<a href="<?php echo esc_url(home_url('/faq')); ?>" class="menu-header__link<?php if (is_page('faq')) echo ' _active'; ?>">FAQ</a>
									</li>
									<li class="menu-header__item">
										<a href="<?php echo esc_url(home_url('/get-started')); ?>" class="menu-header__link button<?php if (is_page('get-started')) echo ' _active'; ?>">Get Started</a>
									</li>
								<?php } ?>
							</ul>
						</nav>
						
						<!-- Mobile Menu Toggle -->
						<button class='burger-menu' aria-label="open menu">
							<span class='burger-menu__line burger-menu__line_1'></span>
							<span class='burger-menu__line burger-menu__line_2'></span>
							<span class='burger-menu__line burger-menu__line_3'></span>
						</button>
					</div>
				</div>
			</header>
		</div>
		
		<!-- Mobile Navigation (outside header for better control) -->
		<div class="mobile-nav">
			<!-- Logo -->
			<div class="header__content">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo" aria-label="link homepage">
					<img width="213" height="45" src="<?php echo esc_url(get_theme_mod('selfscan_logo_dark', get_template_directory_uri() . '/img/main/logo-black.svg')); ?>" alt="<?php bloginfo('name'); ?>">
				</a>
				<button class="mobile-nav__close" aria-label="close menu">
					<span class="mobile-nav__line mobile-nav__line_1"></span>
					<span class="mobile-nav__line mobile-nav__line_2"></span>
				</button>
			</div>
			<ul class="mobile-nav__list">
				<?php
				// Display primary menu
				if (has_nav_menu('primary')) {
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'walker'         => new SelfScan_Walker_Nav_Menu(),
						'fallback_cb'    => false,
					));
				} else {
					// Fallback to static menu if no menu is set
					?>
					<li class="menu-header__item">
						<a href="<?php echo esc_url(home_url('/pricing')); ?>" class="menu-header__link<?php if (is_page('pricing')) echo ' _active'; ?>">Pricing</a>
					</li>
					<li class="menu-header__item">
						<a href="<?php echo esc_url(home_url('/faq')); ?>" class="menu-header__link<?php if (is_page('faq')) echo ' _active'; ?>">FAQ</a>
					</li>
					<li class="menu-header__item">
						<a href="<?php echo esc_url(home_url('/get-started')); ?>" class="menu-header__link button<?php if (is_page('get-started')) echo ' _active'; ?>">Get Started</a>
					</li>
				<?php } ?>
				
				<li class="menu-header__item menu-header__item-social">
					<?php
					// Display social menu
					if (has_nav_menu('social-menu')) {
						wp_nav_menu(array(
							'theme_location' => 'social-menu',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'walker'         => new SelfScan_Walker_Social_Menu(),
							'fallback_cb'    => false,
						));
					} else {
						// Fallback to static social icons if no menu is set
						?>
						<a href="#" class="menu-header__link">
							<?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/linkedin.svg', ['class' => 'menu-header__icon']); ?>
						</a>
						<a href="#" class="menu-header__link">
							<?php selfscan_inline_svg(get_template_directory_uri() . '/img/icons/facebook.svg', ['class' => 'menu-header__icon']); ?>
						</a>
					<?php } ?>
				</li>
			</ul>
		</div>
		
		<main class="page"><?php // Main content starts here, closes in footer.php ?>
