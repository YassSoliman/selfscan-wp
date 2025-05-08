<?php
/**
 * selfscan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package selfscan
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function selfscan_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on selfscan, use a find and replace
		* to change 'selfscan' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'selfscan', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'selfscan' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'selfscan_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'selfscan_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function selfscan_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'selfscan_content_width', 640 );
}
add_action( 'after_setup_theme', 'selfscan_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function selfscan_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'selfscan' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'selfscan' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'selfscan_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function selfscan_scripts() {
	// Check if compiled assets exist and use them
	if ( file_exists( get_template_directory() . '/build/js/main.js' ) ) {
		wp_enqueue_style( 'selfscan-main-style', get_template_directory_uri() . '/build/css/main.css', array(), filemtime( get_template_directory() . '/build/css/main.css' ) );
		wp_enqueue_script( 'selfscan-main-js', get_template_directory_uri() . '/build/js/main.js', array(), filemtime( get_template_directory() . '/build/js/main.js' ), true );
	} else {
		// Fallback to original theme styles if compiled assets don't exist
		wp_enqueue_style( 'selfscan-style', get_stylesheet_uri(), array(), _S_VERSION );
		wp_style_add_data( 'selfscan-style', 'rtl', 'replace' );
		wp_enqueue_script( 'selfscan-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'selfscan_scripts' );


/**
 * Recursively search for files matching a pattern
 *
 * @param string $folder_path Path to search in.
 * @param string $pattern Regex pattern to match files.
 * @return array List of matching file paths.
 */
function recursive_file_search(string $folder_path, string $pattern): array {
    $file_list = [];
    $dir = new RecursiveDirectoryIterator($folder_path);
    $iterator = new RecursiveIteratorIterator($dir);
    $matches = new RegexIterator($iterator, $pattern);
    
    foreach ($matches as $match) {
        $file_list[] = $match->getPathname();
    }
    
    return $file_list;
}

/**
 * Load all PHP files in the includes directory
 */
function load_theme_includes(): void {
    $includes_folder = get_template_directory() . '/inc';
    if (!is_dir($includes_folder)) {
        return;
    }
    
    $file_list = recursive_file_search($includes_folder, '/^.*.php$/');
    foreach ($file_list as $file) {
        require_once $file;
    }
}
add_action('after_setup_theme', 'load_theme_includes', 0);

/**
 * Register navigation menus
 */
function selfscan_register_menus() {
	register_nav_menus(
		array(
			'social-menu'  => esc_html__( 'Social Media Menu', 'selfscan' ),
			'footer-menu'  => esc_html__( 'Footer Bottom Menu', 'selfscan' ),
			'footer-right' => esc_html__( 'Footer Right Menu', 'selfscan' ),
		)
	);
}
add_action( 'after_setup_theme', 'selfscan_register_menus' );

