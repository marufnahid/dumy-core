<?php
/**
 * Plugin Name: Thex Core
 * Plugin URI: https://themeforest.net/maruf
 * Description: This plugin adds the core features to the  WordPress theme. You must have to install this plugin to get all the features included with the this theme.
 * Version: 1.0.0
 * Author: Themexplosion
 * Author URI: https://themeforest.net/themexplosion
 * License: GPLv2 or later
 * Text domain: thexcore
 * Domain Path: /languages/
 */

if ( !defined( 'ABSPATH') ){
	exit();
}


require_once __DIR__."/vendor/autoload.php";

if ( !class_exists( 'Thex_core' ) ) {
	/**
	 * Main Thex Core Class
	 *
	 * The main class that initiates and runs the Docy Core plugin.
	 */
	class Thex_core {
		/**
		 * Docy Core Version
		 *
		 * Holds the version of the plugin.
		 *
		 * @var string The plugin version.
		 */
		const VERSION = '1.0' ;
		/**
		 * Minimum Elementor Version
		 *
		 * Holds the minimum Elementor version required to run the plugin.
		 *
		 * @var string Minimum Elementor version required to run the plugin.
		 */
		const MINIMUM_ELEMENTOR_VERSION = '2.6.0';
		/**
		 * Minimum PHP Version
		 *
		 * Holds the minimum PHP version required to run the plugin.
		 *
		 * @var string Minimum PHP version required to run the plugin.
		 */
		const  MINIMUM_PHP_VERSION = '5.4' ;
		/**
		 * Plugin's directory paths
		 * @since 1.0
		 */

		/**
		 * Instance
		 *
		 * Holds a single instance of the `Thex_Core` class.
		 *
		 * @access private
		 * @static
		 *
		 * @var Thex_Core A single instance of the class.
		 */
		private static  $_instance = null ;
		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @access public
		 * @static
		 *
		 * @return Thex_Core An instance of the class.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Clone
		 *
		 * Disable class cloning.
		 *
		 * @access protected
		 *
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'thexcore' ), '1.0.0' );
		}

		/**
		 * Wakeup
		 *
		 * Disable unserializing the class.
		 *
		 * @access protected
		 *
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'thexcore' ), '1.0.0' );
		}

		/**
		 * Constructor
		 *
		 * Initialize the Docy Core plugins.
		 *
		 * @access public
		 */
		public function __construct() {

			$this->init_hooks();
			$this->includes_files();

		}	


		private function init_hooks() {
			add_action('plugin_loaded',[$this,'thexcore_load_textdomain']);
		}

		/**
		 * Load Textdomain
		 *
		 * Load plugin localization files.
		 *
		 * @access public
		 */
		public function thexcore_load_textdomain() {
			load_plugin_textdomain('thexcore',false,plugin_dir_path(__FILE__)."languages");
			$this->thexcore_elementor_init();
		}

		public function thexcore_elementor_init(){
			if ( $this->is_compatible() ) {
				add_action( 'elementor/init', [ $this, 'init' ] );
			}
		}

		public function is_compatible() {

			// Check if Elementor installed and activated
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
				return false;
			}

			// Check for required Elementor version
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
				return false;
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
				return false;
			}

			return true;

		}

		public function init() {


			// Add Plugin actions
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

			add_action( 'elementor/elements/categories_registered', [$this,'thex_elementor_category' ]);
		//	add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );

		}




		function thex_elementor_category( $elements_manager ) {

			$elements_manager->add_category(
				'thex-addons',
				[
					'title' => __( 'Thex Addons', 'thexcore' ),
					'icon' => 'fa fa-plug',
				],
				999
			);

		}





		public function init_widgets() {

			// Include Widget files
			$this->thexcore_widget_autoload();
			$this->thexcore_widget_register();
			// Register widget


		}

		public function thexcore_widget_autoload(){
			require_once __DIR__."/vendor/autoload.php";
		}

		public function thexcore_widget_register(){
//
//			$widgets = [
//				 'Accordion', 'Button'
//			];
//
//			//$widgets = array_merge($widgets, $forum_widgets);
//
//			foreach ( $widgets as $widget ) {
//				$classname = "\\ThexCore\\ElementorWidget\\Widgets\\$widget";
//			}

				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Thexcore\ThexElementor\Widgets\Accordion() );

		}

//		public function init_controls() {
//
//			// Include Control files
//			require_once( __DIR__ . '/controls/test-control.php' );
//
//			// Register control
//			\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );
//
//		}


		public function admin_notice_missing_main_plugin() {

			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

			$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
				'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>'
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}


		public function admin_notice_minimum_elementor_version() {

			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

			$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
				'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
				self::MINIMUM_ELEMENTOR_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		public function admin_notice_minimum_php_version() {

			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

			$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
				'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'elementor-test-extension' ) . '</strong>',
				self::MINIMUM_PHP_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}








		public  function includes_files(){
			require_once __DIR__."/wp-widgets/widgets.php";
		}




	}
}


if ( !function_exists( 'thexcore_class_load' ) ) {

	function thexcore_class_load(): ?Thex_core {
		return Thex_core::instance();
	}

	thexcore_class_load();
}






