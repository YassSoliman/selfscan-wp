<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package selfscan
 */

if ( ! function_exists( 'selfscan_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function selfscan_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'selfscan' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'selfscan_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function selfscan_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'selfscan' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'selfscan_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function selfscan_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'selfscan' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'selfscan' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'selfscan' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'selfscan' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'selfscan' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'selfscan' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'selfscan_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function selfscan_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

/**
 * Checks whether is prod
 *
 * @return boolean
 */
function selfscan_is_prod() {
	$parsed_url = wp_parse_url( get_option( 'siteurl' ) );
	return 'www.selfscan.ca' === $parsed_url['host'] ? true : false;
}

/**
 * Turns an svg id into an inline svg
 * TODO: sanitize this thing
 *
 * @param mixed $image can be an image id or a string with the full URL
 * @param array $atts
 * @return void
 */
function selfscan_get_inline_svg( $image = null, array $atts = [] ) {
	if ( is_int( $image ) ) {
		$image = wp_get_attachment_url( $image );
	}

	$transient_key = sanitize_key( $image . implode( '-', $atts ) ); // maybe sanitize dashes and stuff?
	$transient_svg = get_transient( $transient_key );
	if ( ! empty( $transient_svg ) ) {
		return $transient_svg;
	}

	// need to disble ssl verify for local dev.
	$response = wp_remote_get( $image, selfscan_is_prod() ? [] : [ 'sslverify' => false ] );
	$svg_raw  = wp_remote_retrieve_body( $response ); // maybe better error handling.
	$dom      = new \DOMDocument();
	// $dom->loadHTML tosses errors for html5 elements like svgs, the only way around it is disabling errors.
	libxml_use_internal_errors( true );
	$dom->loadHTML( $svg_raw, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	// clearing expected errors for above.
	libxml_clear_errors();
	libxml_use_internal_errors( false );
	foreach ( $dom->getElementsByTagName( 'svg' ) as $svg ) {
		foreach ( $atts as $key => $value ) {
			$svg->setAttribute( $key, $value );
		}
	}
	$svg = $dom->saveHTML();

	set_transient( $transient_key, $svg, 0 ); // never expire.
	return $svg;
}

/**
 * Echos out svg inline
 * TODO sanitize this thing
 *
 * @param mixed $image can be either the image id or full url for the src.
 * @param array $atts image attributes just like any other.
 * @return void
 */
function selfscan_inline_svg( $image = null, array $atts = [] ) {
	echo selfscan_get_inline_svg( $image, $atts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
}