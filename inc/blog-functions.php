<?php
/**
 * Blog-related helper functions
 *
 * @package selfscan
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculate reading time for a post
 *
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return int Reading time in minutes
 */
function selfscan_get_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$content = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Average reading speed: 200 words per minute
	
	return max( 1, $reading_time ); // Minimum 1 minute
}

/**
 * Get custom excerpt with specific length
 *
 * @param int $limit Word limit for excerpt
 * @param int $post_id Post ID (optional)
 * @return string Excerpt
 */
function selfscan_get_excerpt( $limit = 30, $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$excerpt = get_post_field( 'post_excerpt', $post_id );
	
	if ( empty( $excerpt ) ) {
		$content = get_post_field( 'post_content', $post_id );
		$excerpt = wp_strip_all_tags( $content );
	}
	
	$words = explode( ' ', $excerpt );
	if ( count( $words ) > $limit ) {
		$excerpt = implode( ' ', array_slice( $words, 0, $limit ) ) . '...';
	}
	
	return $excerpt;
}

/**
 * Get author avatar with fallback
 *
 * @param int $author_id Author ID
 * @param int $size Avatar size
 * @return string Avatar HTML or fallback
 */
function selfscan_get_author_avatar( $author_id = null, $size = 64 ) {
	if ( ! $author_id ) {
		$author_id = get_the_author_meta( 'ID' );
	}
	
	$avatar = get_avatar( $author_id, $size, '', get_the_author_meta( 'display_name', $author_id ) );
	
	if ( $avatar ) {
		return $avatar;
	}
	
	// Fallback to default avatar
	return '<div class="default-avatar" style="width: ' . esc_attr( $size ) . 'px; height: ' . esc_attr( $size ) . 'px; background: #ac000a; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">' . 
		   esc_html( strtoupper( substr( get_the_author_meta( 'display_name', $author_id ), 0, 1 ) ) ) . 
		   '</div>';
}

/**
 * Get related posts based on categories
 *
 * @param int $post_id Current post ID
 * @param int $limit Number of related posts to return
 * @return WP_Query|false Related posts query or false
 */
function selfscan_get_related_posts( $post_id = null, $limit = 3 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$categories = wp_get_post_categories( $post_id );
	
	if ( empty( $categories ) ) {
		return false;
	}
	
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'post__not_in'   => array( $post_id ),
		'category__in'   => $categories,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS'
			)
		)
	);
	
	return new WP_Query( $args );
}

/**
 * Get formatted post date
 *
 * @param int $post_id Post ID
 * @return string Formatted date
 */
function selfscan_get_post_date( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	return get_the_date( 'F j, Y', $post_id );
}

/**
 * Get primary category for a post
 *
 * @param int $post_id Post ID
 * @return WP_Term|false Primary category or false
 */
function selfscan_get_primary_category( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$categories = get_the_category( $post_id );
	
	if ( empty( $categories ) ) {
		return false;
	}
	
	// Return the first category as primary
	return $categories[0];
}

/**
 * Generate share buttons for a post
 *
 * @param int $post_id Post ID
 * @return string Share buttons HTML
 */
function selfscan_get_share_buttons( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$post_url = get_permalink( $post_id );
	$post_title = get_the_title( $post_id );
	$post_excerpt = selfscan_get_excerpt( 20, $post_id );
	
	$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $post_url );
	$twitter_url = 'https://twitter.com/intent/tweet?url=' . urlencode( $post_url ) . '&text=' . urlencode( $post_title );
	$linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $post_url );
	
	ob_start();
	?>
	<div class="social-share">
		<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-facebook">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
			</svg>
		</a>
		<a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-twitter">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
			</svg>
		</a>
		<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-linkedin">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
			</svg>
		</a>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Add custom body classes for blog pages
 *
 * @param array $classes Existing body classes
 * @return array Modified body classes
 */
function selfscan_blog_body_classes( $classes ) {
	if ( is_home() || is_front_page() && is_home() ) {
		$classes[] = 'blog-home';
	}
	
	if ( is_single() && get_post_type() === 'post' ) {
		$classes[] = 'single-post-page';
	}
	
	if ( is_category() || is_tag() || is_archive() ) {
		$classes[] = 'blog-archive';
	}
	
	return $classes;
}
add_filter( 'body_class', 'selfscan_blog_body_classes' );

/**
 * Enqueue blog-specific styles and scripts
 */
function selfscan_blog_assets() {
	if ( is_home() || is_single() || is_category() || is_tag() || is_archive() ) {
		// Blog assets will be included in main.css and main.js
		// This function is a placeholder for future blog-specific assets
	}
}
add_action( 'wp_enqueue_scripts', 'selfscan_blog_assets' );