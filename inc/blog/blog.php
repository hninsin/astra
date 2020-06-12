<?php
/**
 * Blog Helper Functions
 *
 * @package Astra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Adds custom classes to the array of body classes.
 */
if ( ! function_exists( 'astra_blog_body_classes' ) ) {

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function astra_blog_body_classes( $classes ) {

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		return $classes;
	}
}

add_filter( 'body_class', 'astra_blog_body_classes' );

/**
 * Adds custom classes to the array of post grid classes.
 */
if ( ! function_exists( 'astra_post_class_blog_grid' ) ) {

	/**
	 * Adds custom classes to the array of post grid classes.
	 *
	 * @since 1.0
	 * @param array $classes Classes for the post element.
	 * @return array
	 */
	function astra_post_class_blog_grid( $classes ) {

		if ( is_archive() || is_home() || is_search() ) {
			$classes[] = 'ast-col-sm-12';
			$classes[] = 'ast-article-post';
		}

		return $classes;
	}
}

add_filter( 'post_class', 'astra_post_class_blog_grid' );

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
if ( ! function_exists( 'astra_blog_get_post_meta' ) ) {

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since 1.0
	 * @return mixed            Markup.
	 */
	function astra_blog_get_post_meta() {

		$enable_meta = apply_filters( 'astra_blog_post_meta_enabled', '__return_true' );
		$post_meta   = astra_get_option( 'blog-meta' );

		if ( 'post' == get_post_type() && is_array( $post_meta ) && $enable_meta ) {

			$output_str = astra_get_post_meta( $post_meta );

			if ( ! empty( $output_str ) ) {
				echo apply_filters( 'astra_blog_post_meta', '<div class="entry-meta">' . $output_str . '</div>', $output_str ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
}

/**
 * Featured post meta.
 */
if ( ! function_exists( 'astra_blog_post_get_featured_item' ) ) {

	/**
	 * To featured image / gallery / audio / video etc. As per the post format.
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function astra_blog_post_get_featured_item() {

		$post_featured_data = '';
		$post_format        = get_post_format();

		if ( has_post_thumbnail() ) {

			$post_featured_data  = '<a href="' . esc_url( get_permalink() ) . '" >';
			$post_featured_data .= get_the_post_thumbnail();
			$post_featured_data .= '</a>';

		} else {

			switch ( $post_format ) {
				case 'image':
					break;

				case 'video':
					$post_featured_data = astra_get_video_from_post( get_the_ID() );
					break;

				case 'gallery':
					$post_featured_data = get_post_gallery( get_the_ID(), false );
					if ( isset( $post_featured_data['ids'] ) ) {
						$img_ids = explode( ',', $post_featured_data['ids'] );

						$image_alt = get_post_meta( $img_ids[0], '_wp_attachment_image_alt', true );
						$image_url = wp_get_attachment_url( $img_ids[0] );

						if ( isset( $img_ids[0] ) ) {
							$post_featured_data  = '<a href="' . esc_url( get_permalink() ) . '" >';
							$post_featured_data .= '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image_alt ) . '" >';
							$post_featured_data .= '</a>';
						}
					}
					break;

				case 'audio':
					$post_featured_data = do_shortcode( astra_get_audios_from_post( get_the_ID() ) );
					break;
			}
		}

		echo $post_featured_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'astra_blog_post_featured_format', 'astra_blog_post_get_featured_item' );


/**
 * Blog Post Thumbnail / Title & Meta Order
 */
if ( ! function_exists( 'astra_blog_post_thumbnail_and_title_order' ) ) {

	/**
	 * Blog post Thubmnail, Title & Blog Meta order
	 *
	 * @since  1.0.8
	 */
	function astra_blog_post_thumbnail_and_title_order() {

		$blog_post_thumb_title_order = astra_get_option( 'blog-post-structure' );

		if ( is_array( $blog_post_thumb_title_order ) ) {
			// Append the custom class for second element for single post.
			foreach ( $blog_post_thumb_title_order as $post_thumb_title_order ) {

				switch ( $post_thumb_title_order ) {

					// Blog Post Featured Image.
					case 'image':
						do_action( 'astra_blog_archive_featured_image_before' );
						astra_get_blog_post_thumbnail( 'archive' );
						do_action( 'astra_blog_archive_featured_image_after' );
						break;

					// Blog Post Title.
					case 'title-meta':
						do_action( 'astra_blog_archive_title_meta_before' );
						astra_get_blog_post_title_meta();
						do_action( 'astra_blog_archive_title_meta_after' );
						break;

					// Blog Post Meta.
					case 'blog-meta':
						do_action( 'astra_blog_archive_blog_meta_before' );
						astra_get_blog_post_blog_meta();
						do_action( 'astra_blog_archive_blog_meta_after' );
						break;

					// Blog Post Content.
					case 'content':
						do_action( 'astra_entry_content_before' );
						astra_get_blog_post_content();
						do_action( 'astra_entry_content_after' );
						break;
				}
			}
		}
	}
}

/**
 * Blog Archive Blog-Meta Generation
 */
if ( ! function_exists( 'astra_get_blog_post_blog_meta' ) ) {

	/**
	 * Single post content
	 *
	 * @since  x.x.x
	 */
	function astra_get_blog_post_blog_meta() {

		do_action( 'astra_archive_post_meta_before' );

		astra_blog_get_post_meta();

		do_action( 'astra_archive_post_meta_after' );
	}
}

/**
 * Single Post-Meta Generation
 */
if ( ! function_exists( 'astra_get_single_post_meta' ) ) {

	/**
	 * Single post Meta
	 *
	 * @since  x.x.x
	 */
	function astra_get_single_post_meta() {

		do_action( 'astra_single_post_meta_before' );

		astra_single_get_post_meta();

		do_action( 'astra_single_post_meta_after' );
	}
}

/**
 * Single Post Markup Generation
 */
if ( ! function_exists( 'astra_render_single_post_markup' ) ) {

	/**
	 * Single post content
	 *
	 * @since  x.x.x
	 */
	function astra_render_single_post_markup() {

		$single_post_content_structure = astra_get_option( 'blog-single-post-structure' );

		if ( is_array( $single_post_content_structure ) ) {

			foreach ( $single_post_content_structure as $post_meta_order ) {

				switch ( $post_meta_order ) {

					// Single Post Featured Image.
					case 'single-image':
						do_action( 'astra_blog_single_featured_image_before' );
						astra_get_single_post_header();
						do_action( 'astra_blog_single_featured_image_after' );
						break;

					// Single Post Title.
					case 'single-title-meta':
						astra_do_backward_blog_layout_action( 'astra_blog_single_title_meta_before', 'astra_blog_single_post_title_before' );
						astra_get_single_post_title();
						do_action( 'astra_blog_single_post_title_after' );
						break;

					// Single Post Meta.
					case 'single-meta-data':
						do_action( 'astra_blog_single_post_meta_before' );
						astra_get_single_post_meta();
						astra_do_backward_blog_layout_action( 'astra_blog_single_title_meta_after', 'astra_blog_single_post_meta_after' );
						break;

					// Single Post Content.
					case 'single-content':
						do_action( 'astra_blog_single_content_before' );
						astra_get_single_post_content();
						do_action( 'astra_blog_single_content_after' );
						break;
				}
			}
		}
	}
}

/**
 * Single Post Header
 */
if ( ! function_exists( 'astra_get_single_post_header' ) ) {

	/**
	 * Single post content
	 *
	 * @since  x.x.x
	 */
	function astra_get_single_post_header() {

		do_action( 'astra_single_post_header_before' );

		?>
			<header class="entry-header <?php astra_entry_header_class(); ?>">

			<?php do_action( 'astra_single_post_header_top' ); ?>

			<?php astra_get_blog_post_thumbnail( 'single' ); ?>

			<?php astra_do_backward_blog_layout_action( 'astra_single_header_bottom', 'astra_single_post_header_bottom' ); ?>

			</header><!-- .entry-header -->
		<?php

		do_action( 'astra_single_post_header_after' );
	}
}

/**
 * Blog Posts Content
 */
if ( ! function_exists( 'astra_get_blog_post_content' ) ) {

	/**
	 * Blog posts content
	 *
	 * @since  x.x.x
	 */
	function astra_get_blog_post_content() {

		?>
			<div class="entry-content clear"
				<?php
					echo astra_attr(
						'article-entry-content-blog-layout',
						array(
							'class' => '',
						)
					);
				?>
			>
				<?php

					do_action( 'astra_blog_archive_content_before' );
					astra_the_excerpt();
					do_action( 'astra_blog_archive_content_after' );

					wp_link_pages(
						array(
							'before'      => '<div class="page-links">' . esc_html( astra_default_strings( 'string-blog-page-links-before', false ) ),
							'after'       => '</div>',
							'link_before' => '<span class="page-link">',
							'link_after'  => '</span>',
						)
					);
				?>

			</div><!-- .entry-content .clear -->

		<?php
	}
}

/**
 * Single Post Content
 */
if ( ! function_exists( 'astra_get_single_post_content' ) ) {

	/**
	 * Single post content
	 *
	 * @since  x.x.x
	 */
	function astra_get_single_post_content() {

		astra_do_backward_blog_layout_action( 'astra_single_header_after' );

		?>
			<div class="entry-content clear" 
				<?php
					echo astra_attr(
						'article-entry-content-single-layout',
						array(
							'class' => '',
						)
					);
				?>
			>
				<?php

					do_action( 'astra_entry_content_before' );

					the_content();

					astra_edit_post_link(
						sprintf(
							/* translators: %s: Name of current post */
							esc_html__( 'Edit %s', 'astra' ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						),
						'<span class="edit-link">',
						'</span>'
					);

					do_action( 'astra_entry_content_after' );

				?>

				<?php
					wp_link_pages(
						array(
							'before'      => '<div class="page-links">' . esc_html( astra_default_strings( 'string-single-page-links-before', false ) ),
							'after'       => '</div>',
							'link_before' => '<span class="page-link">',
							'link_after'  => '</span>',
						)
					);
				?>
			</div><!-- .entry-content .clear -->
		<?php
	}
}

/**
 * Blog / Single Post Thumbnail
 */
if ( ! function_exists( 'astra_get_blog_post_thumbnail' ) ) {

	/**
	 * Blog post Thumbnail
	 *
	 * @param string $type Type of post.
	 * @since  1.0.8
	 */
	function astra_get_blog_post_thumbnail( $type = 'archive' ) {

		if ( 'archive' === $type ) {
			// Blog Post Featured Image.
			astra_get_post_thumbnail( '<div class="ast-blog-featured-section post-thumb ast-col-md-12">', '</div>' );
		} elseif ( 'single' === $type ) {
			// Single Post Featured Image.
			astra_get_post_thumbnail();
		}
	}
}

/**
 * Blog Post Title & Meta Order
 */
if ( ! function_exists( 'astra_get_blog_post_title_meta' ) ) {

	/**
	 * Blog post Thumbnail
	 *
	 * @since  1.0.8
	 */
	function astra_get_blog_post_title_meta() {

		// Blog Post Title and Blog Post Meta.
		do_action( 'astra_archive_entry_header_before' );
		?>
		<header class="entry-header">
			<?php

				do_action( 'astra_archive_post_title_before' );

				/* translators: 1: Current post link, 2: Current post id */
				astra_the_post_title(
					sprintf(
						'<h2 class="entry-title" %2$s><a href="%1$s" rel="bookmark">',
						esc_url( get_permalink() ),
						astra_attr(
							'article-title-blog',
							array(
								'class' => '',
							)
						)
					),
					'</a></h2>',
					get_the_id()
				);

				do_action( 'astra_archive_post_title_after' );

			?>
		</header><!-- .entry-header -->
		<?php

		do_action( 'astra_archive_entry_header_after' );
	}
}

/**
 * Single Post Title
 */
if ( ! function_exists( 'astra_get_single_post_title' ) ) {

	/**
	 * Blog post Thumbnail
	 *
	 * @since  1.0.8
	 */
	function astra_get_single_post_title() {

		// Single Post Title and Single Post Meta.
		do_action( 'astra_single_post_order_before' );

		astra_do_backward_blog_layout_action( 'astra_single_header_before' );

		astra_do_backward_blog_layout_action( 'astra_single_header_top' );

		?>
		<div class="ast-single-post-order">
			<?php

			do_action( 'astra_single_post_title_before' );

			astra_the_title(
				'<h1 class="entry-title" ' . astra_attr(
					'article-title-blog-single',
					array(
						'class' => '',
					)
				) . '>',
				'</h1>'
			);

			do_action( 'astra_single_post_title_after' );

			?>
		</div>
		<?php

		do_action( 'astra_single_post_order_after' );
	}
}

/**
 * Get audio files from post content
 */
if ( ! function_exists( 'astra_get_audios_from_post' ) ) {

	/**
	 * Get audio files from post content
	 *
	 * @param  number $post_id Post id.
	 * @return mixed          Iframe.
	 */
	function astra_get_audios_from_post( $post_id ) {

		// for audio post type - grab.
		$post    = get_post( $post_id );
		$content = do_shortcode( apply_filters( 'the_content', $post->post_content ) );
		$embeds  = apply_filters( 'astra_get_post_audio', get_media_embedded_in_content( $content ) );

		if ( empty( $embeds ) ) {
			return '';
		}

		// check what is the first embed containg video tag, youtube or vimeo.
		foreach ( $embeds as $embed ) {
			if ( strpos( $embed, 'audio' ) ) {
				return '<span class="ast-post-audio-wrapper">' . $embed . '</span>';
			}
		}
	}
}

/**
 * Get first image from post content
 */
if ( ! function_exists( 'astra_get_video_from_post' ) ) {

	/**
	 * Get first image from post content
	 *
	 * @since 1.0
	 * @param  number $post_id Post id.
	 * @return mixed
	 */
	function astra_get_video_from_post( $post_id ) {

		$post    = get_post( $post_id );
		$content = do_shortcode( apply_filters( 'the_content', $post->post_content ) );
		$embeds  = apply_filters( 'astra_get_post_audio', get_media_embedded_in_content( $content ) );

		if ( empty( $embeds ) ) {
			return '';
		}

		// check what is the first embed containg video tag, youtube or vimeo.
		foreach ( $embeds as $embed ) {
			if ( strpos( $embed, 'video' ) || strpos( $embed, 'youtube' ) || strpos( $embed, 'vimeo' ) ) {
				return $embed;
			}
		}
	}
}
