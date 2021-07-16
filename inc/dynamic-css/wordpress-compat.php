<?php
/**
 * Astra WordPress-5.8 compatibility - Dynamic CSS.
 *
 * @package astra
 * @since x.x.x
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'astra_dynamic_theme_css', 'astra_wordpress_compat_css' );

/**
 * Astra WordPress compatibility - Dynamic CSS.
 *
 * @param string $dynamic_css Dynamic CSS.
 * @since x.x.x
 */
function astra_wordpress_compat_css( $dynamic_css ) {

	if ( Astra_Dynamic_CSS::is_wordpress_5_8_support_enabled() ) {

		$desktop_css = '
		.wp-block-search {
			margin-bottom: 20px;
		}
		.wp-block-site-tagline {
			margin-top: 20px;
		}
        form.wp-block-search .wp-block-search__input, .wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper, .site-content .wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper {
            border-color: #eaeaea;
			background: #fafafa;
        }
		.site-content .wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper .wp-block-search__input:focus, .wp-block-loginout input:focus {
			outline: thin dotted;
		}
		.wp-block-loginout input:focus {
			border-color: transparent;
		}
		.site-content form.wp-block-search .wp-block-search__inside-wrapper .wp-block-search__input {
			padding: 12px;
		}
		.wp-block-search__button svg {
            fill: currentColor;
			width: 20px;
			height: 20px;
        }
		.wp-block-loginout p label {
			display: block;
		}
		.wp-block-loginout p:not(.login-remember):not(.login-submit) input {
			width: 100%;
		}
		.wp-block-loginout .login-remember input {
			width: 1.1rem;
			height: 1.1rem;
			margin: 0 5px 4px 0;
			vertical-align: middle;
		}';

		return $dynamic_css .= Astra_Enqueue_Scripts::trim_css( $desktop_css );
	}

	return $dynamic_css;
}
