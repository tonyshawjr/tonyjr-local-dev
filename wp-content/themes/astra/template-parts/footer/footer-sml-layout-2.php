<?php
/**
 * Template for Small Footer Layout 2
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

$section_1    = astra_get_small_footer( 'footer-sml-section-1' );
$section_2    = astra_get_small_footer( 'footer-sml-section-2' );
$section_wrap = 'ast-row ast-flex';
$sections     = 0;
$sections     = $sections + count( $section_1 );
$sections     = $sections + count( $section_2 );

switch ( $sections ) {

	case '2':
			$section_class = 'ast-small-footer-section-equally ast-col-md-6 ast-col-xs-12';
		break;

	case '3':
			$section_class = 'ast-small-footer-section-equally ast-col-md-4 ast-col-xs-12';
		break;

	case '4':
			$section_class = 'ast-small-footer-section-equally ast-col-md-3 ast-col-xs-12';
		break;

	case '1':
	default:
			$section_class = 'ast-small-footer-section-equally ast-col-xs-12';
	break;
}

?>

<div class="ast-small-footer footer-sml-layout-2">
	<div class="ast-footer-overlay">
		<div class="ast-container">
			<div class="ast-small-footer-wrap" >
				
				<?php if ( '' != $section_wrap ) : ?>
					<div class="<?php echo esc_attr( $section_wrap ); ?>">
				<?php endif; ?>
				
					<?php if ( $section_1 ) : ?>
						<div class="ast-small-footer-section ast-small-footer-section-1 <?php echo esc_attr( $section_class ); ?>" >
							<?php echo wp_kses_post( $section_1 ); ?>
						</div>
				<?php endif; ?>

					<?php if ( $section_2 ) : ?>
						<div class="ast-small-footer-section ast-small-footer-section-2 <?php echo esc_attr( $section_class ); ?>" >
							<?php echo wp_kses_post( $section_2 ); ?>
						</div>
				<?php endif; ?>

				<?php if ( '' != $section_wrap ) : ?>
					</div>
				<?php endif; ?><!-- .ast-row -->

			</div><!-- .ast-small-footer-wrap -->
		</div><!-- .ast-container -->
	</div><!-- .ast-footer-overlay -->
</div><!-- .ast-small-footer-->
