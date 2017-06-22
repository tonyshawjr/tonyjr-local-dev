<?php
/**
 * Visual Composer Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'Visual Composer' not exist then return.
if ( ! class_exists( 'Vc_Manager' ) ) {
	return;
}

/**
 * Astra Visual Composer Compatibility
 */
if ( ! class_exists( 'Astra_Visual_Composer' ) ) :

	/**
	 * Astra Visual Composer Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Visual_Composer {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'vc_before_init',   array( $this, 'vc_set_as_theme' ) );
		}


		/**
		 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
		 *
		 * @since 1.0.0
		 */
		function vc_set_as_theme() {

			if ( function_exists( 'vc_set_as_theme' ) ) {
				vc_set_as_theme( true );
				vc_manager()->disableUpdater( true );
			}
		}
	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Visual_Composer::get_instance();
