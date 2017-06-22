<?php
/**
 * Functions for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Foreground Color
 */
if ( ! function_exists( 'astra_get_foreground_color' ) ) {

	/**
	 * Foreground Color
	 *
	 * @param  string $hex Color code in HEX format.
	 * @return string      Return foreground color depend on input HEX color.
	 */
	function astra_get_foreground_color( $hex ) {

		if ( 'transparent' == $hex || 'false' == $hex || '#' == $hex || empty( $hex ) ) {
			return 'transparent';

		} else {

			// Get clean hex code.
			$hex = str_replace( '#', '', $hex );

			if ( 3 == strlen( $hex ) ) {
				$hex = str_repeat( substr( $hex,0,1 ), 2 ) . str_repeat( substr( $hex,1,1 ), 2 ) . str_repeat( substr( $hex,2,1 ), 2 );
			}

			// Get r, g & b codes from hex code.
			$r   = hexdec( substr( $hex,0,2 ) );
			$g   = hexdec( substr( $hex,2,2 ) );
			$b   = hexdec( substr( $hex,4,2 ) );
			$hex = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;

			return 128 <= $hex ? '#000000' : '#ffffff';
		}
	}
}

/**
 * Generate CSS
 */
if ( ! function_exists( 'astra_css' ) ) {

	/**
	 * Generate CSS
	 *
	 * @param  mixed  $value         CSS value.
	 * @param  string $css_property CSS property.
	 * @param  string $selector     CSS selector.
	 * @param  string $unit         CSS property unit.
	 * @return void               Echo generated CSS.
	 */
	function astra_css( $value = '', $css_property = '', $selector = '', $unit = '' ) {

		if ( $selector ) {
			if ( $css_property && $value ) {

				if ( '' != $unit ) {
					$value .= $unit;
				}

				$css  = $selector;
				$css .= '{';
				$css .= '	' . $css_property . ': ' . $value . ';';
				$css .= '}';

				echo $css;
			}
		}
	}
}

/**
 * Get Font Size value
 */
if ( ! function_exists( 'astra_get_font_css_value' ) ) {

	/**
	 * Get Font CSS value
	 *
	 * Syntax:
	 *
	 *  astra_get_font_css_value( VALUE, DEVICE, UNIT );
	 *
	 * E.g.
	 *
	 *  astra_get_css_value( VALUE, 'desktop', '%' );
	 *  astra_get_css_value( VALUE, 'tablet' );
	 *  astra_get_css_value( VALUE, 'mobile' );
	 *
	 * @param  string $value        CSS value.
	 * @param  string $unit         CSS unit.
	 * @param  string $device       CSS device.
	 * @return mixed                CSS value depends on $unit & $device
	 */
	function astra_get_font_css_value( $value, $unit = 'px', $device = 'desktop' ) {

		// If value is empty or 0 then return blank.
		if ( '' == $value || 0 == $value ) {
			return '';
		}

		$css_val = '';

		switch ( $unit ) {
			case 'em' :
			case '%' :
						$css_val = esc_attr( $value ) . $unit;
				break;

			case 'px':

				if ( is_numeric( $value ) || strpos( $value, 'px' ) ) {
					$value          = intval( $value );
					$body_font_size = astra_get_option( 'font-size-body' );
					$body_font_size['desktop'] = ( '' != $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 15;
					$body_font_size['tablet'] = ( '' != $body_font_size['tablet'] ) ? $body_font_size['tablet'] : $body_font_size['desktop'];
					$body_font_size['mobile'] = ( '' != $body_font_size['mobile'] ) ? $body_font_size['mobile'] : $body_font_size['tablet'];

					if ( $body_font_size[ $device ] ) {
						$css_val = esc_attr( $value ) . 'px;font-size:' . ( esc_attr( $value ) / esc_attr( $body_font_size[ $device ] ) ) . 'rem';
					}
				} else {
					$css_val = esc_attr( $value );
				}
		}

		return $css_val;
	}
}// End if().

/**
 * Get CSS value
 */
if ( ! function_exists( 'astra_get_css_value' ) ) {

	/**
	 * Get CSS value
	 *
	 * Syntax:
	 *
	 *  astra_get_css_value( VALUE, UNIT );
	 *
	 * E.g.
	 *
	 *  astra_get_css_value( VALUE, 'url' );
	 *  astra_get_css_value( VALUE, 'px' );
	 *  astra_get_css_value( VALUE, 'em' );
	 *
	 * @param  string $value        CSS value.
	 * @param  string $unit         CSS unit.
	 * @param  string $default      CSS default font.
	 * @return mixed               CSS value depends on $unit
	 */
	function astra_get_css_value( $value = '', $unit = 'px', $default = '' ) {

		if ( '' == $value &&  '' == $default ) {
			return $value;
		}

		$css_val = '';

		switch ( $unit ) {

			case 'font' :

				if ( 'inherit' != $value ) {
					$css_val = esc_attr( $value );
				} elseif ( '' != $default ) {
					$css_val = esc_attr( $default );
				}

				break;

			case 'px':
			case '%' :
						$value = ( '' != $value ) ? $value : $default;
						$css_val = esc_attr( $value ) . $unit;
				break;

			case 'url' :
						$css_val = $unit . '(' . esc_url( $value ) . ')';
				break;

			case 'rem':

				if ( is_numeric( $value ) || strpos( $value, 'px' ) ) {
					$value          = intval( $value );
					$body_font_size = astra_get_option( 'font-size-body' );
					$body_font_size['desktop'] = ( '' != $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 15;

					if ( $body_font_size['desktop'] ) {
						$css_val = esc_attr( $value ) . 'px;font-size:' . ( esc_attr( $value ) / esc_attr( $body_font_size['desktop'] ) ) . $unit;
					}
				} else {
					$css_val = esc_attr( $value );
				}

				break;

			default:
				$value = ( '' != $value ) ? $value : $default;
				if ( '' != $value ) {
					$css_val = esc_attr( $value ) . $unit;
				}
		}// End switch().

		return $css_val;
	}
}// End if().

/**
 * Parse CSS
 */
if ( ! function_exists( 'astra_parse_css' ) ) {

	/**
	 * Parse CSS
	 *
	 * @param  array  $css_output Array of CSS.
	 * @param  string $min_media  Min Media breakpoint.
	 * @param  string $max_media  Max Media breakpoint.
	 * @return string             Generated CSS.
	 */
	function astra_parse_css( $css_output = array(), $min_media = '', $max_media = '' ) {

		$parse_css = '';
		if ( is_array( $css_output ) && count( $css_output ) > 0 ) {

			foreach ( $css_output as $selector => $properties ) {

				if ( ! count( $properties ) ) { continue; }

				$temp_parse_css   = $selector . '{';
				$properties_added = 0;

				foreach ( $properties as $property => $value ) {

					if ( '' === $value ) { continue; }

					$properties_added++;
					$temp_parse_css .= $property . ':' . $value . ';';
				}

				$temp_parse_css .= '}';

				if ( $properties_added > 0 ) {
					$parse_css .= $temp_parse_css;
				}
			}

			if ( '' != $parse_css && ( '' !== $min_media || '' !== $max_media ) ) {

				$media_css       = '@media ';
				$min_media_css   = '';
				$max_media_css   = '';
				$media_separator = '';

				if ( '' !== $min_media ) {
					$min_media_css = '(min-width:' . $min_media . 'px)';
				}
				if ( '' !== $max_media ) {
					$max_media_css = '(max-width:' . $max_media . 'px)';
				}
				if ( '' !== $min_media && '' !== $max_media ) {
					$media_separator = ' and ';
				}

				$media_css .= $min_media_css . $media_separator . $max_media_css . '{' . $parse_css . '}';

				return $media_css;
			}
		}// End if().

		return $parse_css;
	}
}// End if().

/**
 * Return Theme options.
 */
if ( ! function_exists( 'astra_get_option' ) ) {

	/**
	 * Return Theme options.
	 *
	 * @param  string $option  		Option key.
	 * @param  string $default 		Option default value.
	 * @param  string $deprecated 	Option default value.
	 * @return string          		Return option value.
	 */
	function astra_get_option( $option, $default = '', $deprecated = '' ) {

		if ( '' != $deprecated ) {
			$default = $deprecated;
		}

		$theme_options = Astra_Theme_Options::get_options();

		$value = ( isset( $theme_options[ $option ] ) && '' !== $theme_options[ $option ] ) ? $theme_options[ $option ] : $default;

		return $value;
	}
}

/**
 * Return Theme options from postmeta.
 */
if ( ! function_exists( 'astra_get_option_meta' ) ) {

	/**
	 * Return Theme options from postmeta.
	 *
	 * @param  string  $option_id Option ID.
	 * @param  string  $default   Option default value.
	 * @param  boolean $only_meta Get only meta value.
	 * @param  string  $extension Is value from extension.
	 * @param  string  $post_id   Get value from specific post by post ID.
	 * @return string             Return option value.
	 */
	function astra_get_option_meta( $option_id, $default = '', $only_meta = false, $extension = '', $post_id = '' ) {

		$post_id = ( '' != $post_id ) ? $post_id : astra_get_post_id();

		$value = astra_get_option( $option_id, $default );

		// Get value from option 'post-meta'.
		if ( is_singular() || ( is_home() && ! is_front_page() ) ) {

			$value = get_post_meta( $post_id, $option_id, true );

			if ( empty( $value ) || 'default' == $value ) {

				if ( true == $only_meta ) {
					return false;
				}

				$value = astra_get_option( $option_id, $default );
			}
		}

		return $value;
	}
}// End if().

/**
 * Helper function to get the current post id.
 */
if ( ! function_exists( 'astra_get_post_id' ) ) {

	/**
	 * Get post ID.
	 *
	 * @param  string $post_id_override Get override post ID.
	 * @return number                   Post ID.
	 */
	function astra_get_post_id( $post_id_override = '' ) {

		if ( null == Astra_Theme_Options::$post_id ) {
			global $post;

			$post_id = 0;

			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' );
			} elseif ( is_archive() ) {
				global $wp_query;
				$post_id = $wp_query->get_queried_object_id();
			} elseif ( isset( $post->ID ) && ! is_search() && ! is_category() ) {
				$post_id = $post->ID;
			}

			Astra_Theme_Options::$post_id = $post_id;
		}

		return apply_filters( 'astra_get_post_id', Astra_Theme_Options::$post_id, $post_id_override );
	}
}


/**
 * Display classes for primary div
 */
if ( ! function_exists( 'astra_primary_class' ) ) {

	/**
	 * Display classes for primary div
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return void        Echo classes.
	 */
	function astra_primary_class( $class = '' ) {

		// Separates classes with a single space, collates classes for body element.
		echo 'class="' . esc_attr( join( ' ', astra_get_primary_class( $class ) ) ) . '"';
	}
}

/**
 * Retrieve the classes for the primary element as an array.
 */
if ( ! function_exists( 'astra_get_primary_class' ) ) {

	/**
	 * Retrieve the classes for the primary element as an array.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array        Return array of classes.
	 */
	function astra_get_primary_class( $class = '' ) {

		// array of class names.
		$classes = array();

		// default class for content area.
		$classes[] = 'content-area';

		// primary base class.
		$classes[] = 'primary';

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {

			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		// Filter primary div class names.
		$classes = apply_filters( 'astra_primary_class', $classes, $class );

		$classes = array_map( 'sanitize_html_class', $classes );

		return array_unique( $classes );
	}
}// End if().

/**
 * Display classes for secondary div
 */
if ( ! function_exists( 'astra_secondary_class' ) ) {

	/**
	 * Retrieve the classes for the secondary element as an array.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return void        echo classes.
	 */
	function astra_secondary_class( $class = '' ) {

		// Separates classes with a single space, collates classes for body element.
		echo 'class="' . esc_attr( join( ' ', get_astra_secondary_class( $class ) ) ) . '"';
	}
}

/**
 * Retrieve the classes for the secondary element as an array.
 */
if ( ! function_exists( 'get_astra_secondary_class' ) ) {

	/**
	 * Retrieve the classes for the secondary element as an array.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array        Return array of classes.
	 */
	function get_astra_secondary_class( $class = '' ) {

		// array of class names.
		$classes = array();

		// default class from widget area.
		$classes[] = 'widget-area';

		// secondary base class.
		$classes[] = 'secondary';

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {

			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		// Filter secondary div class names.
		$classes = apply_filters( 'astra_secondary_class', $classes, $class );

		$classes = array_map( 'sanitize_html_class', $classes );

		return array_unique( $classes );
	}
}// End if().

/**
 * Get post format
 */
if ( ! function_exists( 'astra_get_post_format' ) ) {

	/**
	 * Get post format
	 *
	 * @param  string $post_format_override Override post formate.
	 * @return string                       Return post format.
	 */
	function astra_get_post_format( $post_format_override = '' ) {

		if ( ( is_home() ) || is_archive() ) {
			$post_format = 'blog';
		} else {
			$post_format = get_post_format();
		}

		return apply_filters( 'astra_get_post_format', $post_format, $post_format_override );
	}
}

/**
 * Wrapper function fot the_title()
 */
if ( ! function_exists( 'astra_the_title' ) ) {

	/**
	 * Wrapper function fot the_title()
	 *
	 * Displays title only if the page title bar is disabled.
	 *
	 * @param string $before Optional. Content to prepend to the title.
	 * @param string $after  Optional. Content to append to the title.
	 * @param bool   $echo   Optional, default to true.Whether to display or return.
	 * @return string|void String if $echo parameter is false.
	 */
	function astra_the_title( $before = '', $after = '', $echo = true ) {

		if ( apply_filters( 'astra_the_title_enabled', true ) ) {

			$title  = astra_get_the_title();
			$before = apply_filters( 'astra_the_title_before', '' ) . $before;
			$after  = $after . apply_filters( 'astra_the_title_after', '' );

			// This will work same as `the_title` function but with Custom Title if exits.
			if ( $echo ) {
				echo $before . $title . $after;
			} else {
				return $before . $title . $after;
			}
		}
	}
}

/**
 * Wrapper function fot get_the_title()
 */
if ( ! function_exists( 'astra_get_the_title' ) ) {

	/**
	 * Wrapper function fot get_the_title()
	 *
	 * Return title for Title Bar and Normal Title.
	 *
	 * @param bool $echo   Optional, default to false. Whether to display or return.
	 * @return string|void String if $echo parameter is false.
	 */
	function astra_get_the_title( $echo = false ) {

		$id    = astra_get_post_id();
		$title = '';

		// for 404 page - title always display.
		if ( is_404() ) {

			$title = apply_filters( 'astra_the_404_page_title', esc_html( 'This page doesn\'t seem to exist.', 'astra' ) );

			// for search page - title always display.
		} elseif ( is_search() ) {

			/* translators: 1: search string */
			$title = apply_filters( 'astra_the_search_page_title', sprintf( __( 'Search Results for: %s', 'astra' ), '<span>' . get_search_query() . '</span>' ) );

		} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {

			$title = woocommerce_page_title();

		} elseif ( is_archive() ) {

			$title = get_the_archive_title();

		} else {

			$title = get_the_title( $id );
		}

		// This will work same as `get_the_title` function but with Custom Title if exits.
		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}
}// End if().

/**
 * Archive Page Title
 */
if ( ! function_exists( 'astra_archive_page_info' ) ) {

	/**
	 * Wrapper function fot the_title()
	 *
	 * Displays title only if the page title bar is disabled.
	 */
	function astra_archive_page_info() {

		if ( apply_filters( 'astra_the_title_enabled', true ) ) {

			// Author.
			if ( is_author() ) { ?>

				<section class="ast-author-box ast-archive-description">
					<div class="ast-author-bio">
						 <h1 class='page-title ast-archive-title'><?php echo get_the_author(); ?></h1>
						 <p><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
					</div>
					<div class="ast-author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'email' ) , 120 ); ?>
					</div>
				</section>

			<?php

			// Category.
			} elseif ( is_category() ) { ?>

				<section class="ast-archive-description">
					<h1 class="page-title ast-archive-title"><?php echo single_cat_title(); ?></h1>
					<?php the_archive_description(); ?>
				</section>

			<?php

			// Tag.
			} elseif ( is_tag() ) { ?>

				<section class="ast-archive-description">
					<h1 class="page-title ast-archive-title"><?php echo single_tag_title(); ?></h1>
					<?php the_archive_description(); ?>
				</section>

			<?php

			// Search.
			} elseif ( is_search() ) { ?>

				<section class="ast-archive-description">
					<?php
						/* translators: 1: search string */
						$title = apply_filters( 'astra_the_search_page_title', sprintf( __( 'Search Results for: %s', 'astra' ), '<span>' . get_search_query() . '</span>' ) );
					?>
					<h1 class="page-title ast-archive-title"> <?php echo $title; ?> </h1>
				</section>

			<?php

			// Other.
			} else { ?>

				<section class="ast-archive-description">
					<?php the_archive_title( '<h1 class="page-title ast-archive-title">', '</h1>' ); ?>
					<?php the_archive_description(); ?>
				</section>

		<?php }// End if().
		}// End if().
	}

	add_action( 'astra_archive_header', 'astra_archive_page_info' );
}// End if().


/**
 * Adjust the HEX color brightness
 */
if ( ! function_exists( 'astra_adjust_brightness' ) ) {

	/**
	 * Adjust Brightness
	 *
	 * @param  string $hex   Color code in HEX.
	 * @param  number $steps brightness value.
	 * @param  string $type  brightness is reverse or default.
	 * @return string        Color code in HEX.
	 */
	function astra_adjust_brightness( $hex, $steps, $type ) {

		// Get rgb vars.
		$hex = str_replace( '#','',$hex );

		$shortcode_atts = array(
			'r' => hexdec( substr( $hex,0,2 ) ),
			'g' => hexdec( substr( $hex,2,2 ) ),
			'b' => hexdec( substr( $hex,4,2 ) ),
		);

		// Should we darken the color?
		if ( 'reverse' == $type && $shortcode_atts['r'] + $shortcode_atts['g'] + $shortcode_atts['b'] > 382 ) {
			$steps = -$steps;
		} elseif ( 'darken' == $type ) {
			$steps = -$steps;
		}

		// Build the new color.
		$steps = max( -255, min( 255, $steps ) );

		$shortcode_atts['r']  = max( 0,min( 255,$shortcode_atts['r'] + $steps ) );
		$shortcode_atts['g'] = max( 0,min( 255,$shortcode_atts['g'] + $steps ) );
		$shortcode_atts['b'] = max( 0,min( 255,$shortcode_atts['b'] + $steps ) );

		$r_hex = str_pad( dechex( $shortcode_atts['r'] ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $shortcode_atts['g'] ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $shortcode_atts['b'] ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}
}// End if().

