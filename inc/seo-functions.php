<?php
/**
 * SEO and Schema Markup Functions
 *
 * @package selfscan
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Schema.org JSON-LD markup to head
 */
function selfscan_add_schema_markup() {
	if ( is_single() && get_post_type() === 'post' ) {
		selfscan_add_article_schema();
	} elseif ( is_home() || is_archive() ) {
		selfscan_add_blog_schema();
	}
}
add_action( 'wp_head', 'selfscan_add_schema_markup' );

/**
 * Add Article Schema markup for single posts
 */
function selfscan_add_article_schema() {
	global $post;
	
	$author_id = get_the_author_meta( 'ID' );
	$primary_category = selfscan_get_primary_category();
	
	$schema = [
		'@context' => 'https://schema.org',
		'@type' => 'Article',
		'headline' => get_the_title(),
		'description' => selfscan_get_excerpt( 30 ),
		'datePublished' => get_the_date( 'c' ),
		'dateModified' => get_the_modified_date( 'c' ),
		'author' => [
			'@type' => 'Person',
			'name' => get_the_author(),
			'url' => get_author_posts_url( $author_id )
		],
		'publisher' => [
			'@type' => 'Organization',
			'name' => get_bloginfo( 'name' ),
			'url' => home_url(),
			'logo' => [
				'@type' => 'ImageObject',
				'url' => get_template_directory_uri() . '/img/logo.png',
				'width' => 200,
				'height' => 60
			]
		],
		'mainEntityOfPage' => [
			'@type' => 'WebPage',
			'@id' => get_permalink()
		],
		'url' => get_permalink(),
		'wordCount' => str_word_count( wp_strip_all_tags( get_the_content() ) ),
		'timeRequired' => 'PT' . selfscan_get_reading_time() . 'M'
	];
	
	// Add featured image if available
	if ( has_post_thumbnail() ) {
		$image_id = get_post_thumbnail_id();
		$image_data = wp_get_attachment_image_src( $image_id, 'full' );
		
		if ( $image_data ) {
			$schema['image'] = [
				'@type' => 'ImageObject',
				'url' => $image_data[0],
				'width' => $image_data[1],
				'height' => $image_data[2]
			];
		}
	}
	
	// Add category if available
	if ( $primary_category ) {
		$schema['articleSection'] = $primary_category->name;
	}
	
	// Add tags if available
	$tags = get_the_tags();
	if ( $tags ) {
		$schema['keywords'] = implode( ', ', wp_list_pluck( $tags, 'name' ) );
	}
	
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

/**
 * Add Blog/Website Schema markup for archive pages
 */
function selfscan_add_blog_schema() {
	$schema = [
		'@context' => 'https://schema.org',
		'@type' => 'Blog',
		'name' => get_bloginfo( 'name' ) . ' Blog',
		'description' => get_bloginfo( 'description' ),
		'url' => is_home() ? home_url( '/blog/' ) : get_pagenum_link(),
		'inLanguage' => get_locale(),
		'publisher' => [
			'@type' => 'Organization',
			'name' => get_bloginfo( 'name' ),
			'url' => home_url(),
			'logo' => [
				'@type' => 'ImageObject',
				'url' => get_template_directory_uri() . '/img/logo.png',
				'width' => 200,
				'height' => 60
			]
		]
	];
	
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

/**
 * Add Open Graph meta tags for social sharing
 */
function selfscan_add_open_graph_tags() {
	if ( is_single() && get_post_type() === 'post' ) {
		echo '<meta property="og:type" content="article" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( selfscan_get_excerpt( 30 ) ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		
		// Add featured image
		if ( has_post_thumbnail() ) {
			$image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
			if ( $image_data ) {
				echo '<meta property="og:image" content="' . esc_url( $image_data[0] ) . '" />' . "\n";
				echo '<meta property="og:image:width" content="' . esc_attr( $image_data[1] ) . '" />' . "\n";
				echo '<meta property="og:image:height" content="' . esc_attr( $image_data[2] ) . '" />' . "\n";
			}
		}
		
		// Article specific tags
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '" />' . "\n";
		echo '<meta property="article:author" content="' . esc_attr( get_the_author() ) . '" />' . "\n";
		
		// Add tags
		$tags = get_the_tags();
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
			}
		}
		
		// Add category
		$primary_category = selfscan_get_primary_category();
		if ( $primary_category ) {
			echo '<meta property="article:section" content="' . esc_attr( $primary_category->name ) . '" />' . "\n";
		}
	} elseif ( is_home() || is_archive() ) {
		echo '<meta property="og:type" content="website" />' . "\n";
		
		if ( is_home() ) {
			echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) . ' Blog' ) . '" />' . "\n";
			echo '<meta property="og:description" content="' . esc_attr( 'Latest articles and insights from ' . get_bloginfo( 'name' ) ) . '" />' . "\n";
			echo '<meta property="og:url" content="' . esc_url( home_url( '/blog/' ) ) . '" />' . "\n";
		} else {
			$archive_title = get_the_archive_title();
			$archive_description = get_the_archive_description();
			
			echo '<meta property="og:title" content="' . esc_attr( $archive_title ) . '" />' . "\n";
			if ( $archive_description ) {
				echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( $archive_description ) ) . '" />' . "\n";
			}
			echo '<meta property="og:url" content="' . esc_url( get_pagenum_link() ) . '" />' . "\n";
		}
		
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'selfscan_add_open_graph_tags' );

/**
 * Add Twitter Card meta tags
 */
function selfscan_add_twitter_card_tags() {
	if ( is_single() && get_post_type() === 'post' ) {
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr( selfscan_get_excerpt( 30 ) ) . '" />' . "\n";
		
		// Add featured image
		if ( has_post_thumbnail() ) {
			$image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
			if ( $image_data ) {
				echo '<meta name="twitter:image" content="' . esc_url( $image_data[0] ) . '" />' . "\n";
			}
		}
	} elseif ( is_home() || is_archive() ) {
		echo '<meta name="twitter:card" content="summary" />' . "\n";
		
		if ( is_home() ) {
			echo '<meta name="twitter:title" content="' . esc_attr( get_bloginfo( 'name' ) . ' Blog' ) . '" />' . "\n";
			echo '<meta name="twitter:description" content="' . esc_attr( 'Latest articles and insights from ' . get_bloginfo( 'name' ) ) . '" />' . "\n";
		} else {
			$archive_title = get_the_archive_title();
			$archive_description = get_the_archive_description();
			
			echo '<meta name="twitter:title" content="' . esc_attr( $archive_title ) . '" />' . "\n";
			if ( $archive_description ) {
				echo '<meta name="twitter:description" content="' . esc_attr( wp_strip_all_tags( $archive_description ) ) . '" />' . "\n";
			}
		}
	}
}
add_action( 'wp_head', 'selfscan_add_twitter_card_tags' );

/**
 * Improve SEO title tags
 */
function selfscan_improve_document_title( $title_parts ) {
	if ( is_single() && get_post_type() === 'post' ) {
		// For single posts, use: "Post Title | Site Name"
		$title_parts['title'] = get_the_title();
		$title_parts['site'] = get_bloginfo( 'name' );
		$title_parts['tagline'] = '';
	} elseif ( is_home() ) {
		// For blog home, use: "Blog | Site Name"
		$title_parts['title'] = __( 'Blog', 'selfscan' );
		$title_parts['site'] = get_bloginfo( 'name' );
		$title_parts['tagline'] = '';
	} elseif ( is_category() || is_tag() || is_archive() ) {
		// For archives, use: "Category/Tag Name | Blog | Site Name"
		$archive_title = single_term_title( '', false );
		if ( $archive_title ) {
			$title_parts['title'] = $archive_title;
		}
		$title_parts['page'] = __( 'Blog', 'selfscan' );
		$title_parts['site'] = get_bloginfo( 'name' );
		$title_parts['tagline'] = '';
	}
	
	return $title_parts;
}
add_filter( 'document_title_parts', 'selfscan_improve_document_title' );

/**
 * Add canonical URL for better SEO
 */
function selfscan_add_canonical_url() {
	if ( is_single() || is_page() || is_home() || is_archive() ) {
		$canonical_url = '';
		
		if ( is_single() || is_page() ) {
			$canonical_url = get_permalink();
		} elseif ( is_home() ) {
			$canonical_url = home_url( '/blog/' );
		} elseif ( is_archive() ) {
			$canonical_url = get_pagenum_link( get_query_var( 'paged' ) );
		}
		
		if ( $canonical_url ) {
			echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
		}
	}
}
add_action( 'wp_head', 'selfscan_add_canonical_url' );

/**
 * Add meta description for pages
 */
function selfscan_add_meta_description() {
	$description = '';
	
	if ( is_single() && get_post_type() === 'post' ) {
		$description = selfscan_get_excerpt( 30 );
	} elseif ( is_home() ) {
		$description = 'Latest articles and insights from ' . get_bloginfo( 'name' ) . '. Stay updated with our blog.';
	} elseif ( is_category() || is_tag() ) {
		$term_description = term_description();
		if ( $term_description ) {
			$description = wp_strip_all_tags( $term_description );
		} else {
			$term_name = single_term_title( '', false );
			$description = sprintf( 'Browse articles in %s category on %s blog.', $term_name, get_bloginfo( 'name' ) );
		}
	} elseif ( is_archive() ) {
		$description = 'Browse our archive of articles and insights.';
	}
	
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'selfscan_add_meta_description' );

/**
 * Add robots meta tag for SEO
 */
function selfscan_add_robots_meta() {
	if ( is_search() || is_404() || is_attachment() ) {
		echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
	} elseif ( is_archive() && ! is_category() && ! is_tag() ) {
		// For date archives and other archives, prevent indexing but allow following
		echo '<meta name="robots" content="noindex, follow" />' . "\n";
	}
}
add_action( 'wp_head', 'selfscan_add_robots_meta' );