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
	// Enqueue Plyr CSS and JS from CDN
	wp_enqueue_style( 'plyr-css', 'https://cdn.plyr.io/3.7.8/plyr.css', array(), '3.7.8' );
	wp_enqueue_script( 'plyr-js', 'https://cdn.plyr.io/3.7.8/plyr.polyfilled.js', array(), '3.7.8', true );
	
	// Check if compiled assets exist and use them
	if ( file_exists( get_template_directory() . '/build/js/main.js' ) ) {
		wp_enqueue_style( 'selfscan-main-style', get_template_directory_uri() . '/build/css/main.css', array( 'plyr-css' ), filemtime( get_template_directory() . '/build/css/main.css' ) );
		wp_enqueue_script( 'selfscan-main-js', get_template_directory_uri() . '/build/js/main.js', array( 'plyr-js' ), filemtime( get_template_directory() . '/build/js/main.js' ), true );
		
		// Localize script for AJAX
		wp_localize_script( 'selfscan-main-js', 'wp_ajax_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'load_more_articles' )
		));
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

add_action('wp_head', 'google_tag_manager_head', 20);
function google_tag_manager_head() { ?>
	 <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KQ58DJG');</script>
    <!-- End Google Tag Manager -->
<?php }

add_action('tb_before_header', 'google_tag_manager_body', 100);
function google_tag_manager_body() { ?>
	<!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KQ58DJG"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php }

/**
 * AJAX handler for getting attachment URL
 */
function selfscan_get_attachment_url() {
    $attachment_id = intval($_POST['attachment_id']);
    
    if (!$attachment_id) {
        wp_send_json_error('Invalid attachment ID');
        return;
    }
    
    $url = wp_get_attachment_image_url($attachment_id, 'full');
    
    if ($url) {
        wp_send_json_success(array('url' => $url));
    } else {
        wp_send_json_error('Attachment not found');
    }
}
add_action('wp_ajax_get_attachment_url', 'selfscan_get_attachment_url');
add_action('wp_ajax_nopriv_get_attachment_url', 'selfscan_get_attachment_url');

/**
 * AJAX handler for getting complete attachment image HTML (like wp_get_attachment_image)
 */
function selfscan_get_attachment_image() {
    $attachment_id = intval($_POST['attachment_id']);
    
    if (!$attachment_id) {
        wp_send_json_error('Invalid attachment ID');
        return;
    }
    
    $img_attrs = array(
        'loading' => 'lazy',
        'alt' => 'partner logo',
        'data-image-id' => $attachment_id
    );
    
    $image_html = wp_get_attachment_image($attachment_id, 'full', false, $img_attrs);
    
    if ($image_html) {
        wp_send_json_success(array('html' => $image_html));
    } else {
        wp_send_json_error('Attachment not found');
    }
}
add_action('wp_ajax_get_attachment_image', 'selfscan_get_attachment_image');
add_action('wp_ajax_nopriv_get_attachment_image', 'selfscan_get_attachment_image');

/**
 * AJAX handler for loading more articles
 */
function selfscan_load_more_articles() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'load_more_articles')) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    $offset = intval($_POST['offset']);
    $posts_per_page = 3; // Load 3 more articles at a time
    
    $posts_query = new WP_Query([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'offset' => $offset,
        'meta_query' => [
            [
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            ]
        ]
    ]);
    
    if ($posts_query->have_posts()) {
        ob_start();
        
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            $primary_category = selfscan_get_primary_category();
            ?>
            <article class="articles-blog-body__article" onclick="window.location.href='<?php echo esc_url(get_permalink()); ?>'">
                <div class="articles-blog-body__info">
                    <div class="articles-blog-body__link">
                        <h3 class="articles-blog-body__label">
                            <?php the_title(); ?>
                        </h3>
                    </div>
                    <div class="articles-blog-body__text">
                        <?php echo esc_html(selfscan_get_excerpt(25)); ?>
                    </div>
                    <div class="articles-blog-body__footer">
                        <div class="articles-blog-body__items">
                            <div class="articles-blog-body__item" data-article-autor>
                                <div class="articles-blog-body__icon">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/autor-icon.svg'); ?>" alt="">
                                </div>
                                <div class="articles-blog-body__value">
                                    <?php the_author(); ?>
                                </div>
                            </div>
                            <div class="articles-blog-body__item" data-article-date-published>
                                <div class="articles-blog-body__icon">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/date-icon.svg'); ?>" alt="">
                                </div>
                                <div class="articles-blog-body__value">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date('F j, Y')); ?>
                                    </time>
                                </div>
                            </div>
                            <div class="articles-blog-body__item" data-article-reading-time>
                                <div class="articles-blog-body__value">
                                    <?php echo esc_html(selfscan_get_reading_time()); ?> min read
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="articles-blog-body__decor">
                    <?php if ($primary_category) : ?>
                        <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>" class="articles-blog-body__category" onclick="event.stopPropagation();">
                            <?php echo esc_html($primary_category->name); ?>
                        </a>
                    <?php endif; ?>
                    <div class="articles-blog-body__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium', ['loading' => 'lazy', 'alt' => 'article-image']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php
        }
        
        $articles_html = ob_get_clean();
        wp_reset_postdata();
        
        // Check if there are more posts
        $new_offset = $offset + $posts_per_page;
        $next_posts_query = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'offset' => $new_offset,
            'meta_query' => [
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ]
            ]
        ]);
        
        $has_more = $next_posts_query->have_posts();
        wp_reset_postdata();
        
        wp_send_json_success([
            'html' => $articles_html,
            'new_offset' => $new_offset,
            'has_more' => $has_more
        ]);
    } else {
        wp_send_json_success([
            'html' => '',
            'new_offset' => $offset,
            'has_more' => false
        ]);
    }
}
add_action('wp_ajax_load_more_articles', 'selfscan_load_more_articles');
add_action('wp_ajax_nopriv_load_more_articles', 'selfscan_load_more_articles');
