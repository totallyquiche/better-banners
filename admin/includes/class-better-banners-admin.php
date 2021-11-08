<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Admin;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\Hook_Registrar;

final class Better_Banners_Admin {
	/**
	 * The registrar which handles all hooks (actions & filters).
	 *
	 * @var Hook_Registrar
	 */
	private Hook_Registrar $hook_registrar;

	/**
	 * Enqueue admin styles and scripts on object instantiation.
	 *
	 * @param Hook_Registrar $hook_registrar
	 *
	 * @return void
	 */
	public function __construct( Hook_Registrar $hook_registrar ) {
		$this->hook_registrar = $hook_registrar;

		$this->enqueue_styles();
		$this->enqueue_scripts();
		$this->register_hooks();

		$this->hook_registrar->add();
	}

	/**
	 * Enqueue admin styles.
	 *
	 * @return void
	 */
	private function enqueue_styles() : void {
		wp_enqueue_style(
			Better_Banners::PLUGIN_PREFIX . '_admin_css',
			plugin_dir_url( __FILE__ ) . '../assets/admin.css'
		);

		if ( self::is_current_page_banner_page() ) {
			wp_enqueue_style(
				Better_Banners::PLUGIN_PREFIX . '_plugin_css',
				plugin_dir_url( __FILE__ ) . '../assets/plugin.css'
			);
		}

		if ( self::is_current_page_banner_post_page() ) {
			wp_enqueue_style('wp-color-picker');

			wp_enqueue_style(
				Better_Banners::PLUGIN_PREFIX . '_post_post_css',
				plugin_dir_url( __FILE__ ) . '../assets/post.css'
			);
		}

		if ( self::is_current_page_banner_table_page() ) {
			wp_enqueue_style(
				Better_Banners::PLUGIN_PREFIX . '_table_table_css',
				plugin_dir_url( __FILE__ ) . '../assets/table.css'
			);
		}
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @return void
	 */
	private function enqueue_scripts() : void {
		wp_enqueue_script(
			Better_Banners::PLUGIN_PREFIX . '_admin_js',
			plugin_dir_url( __FILE__ ) . '../assets/admin.js',
			array('jquery')
		);

		if ( self::is_current_page_banner_post_page() ) {
			wp_enqueue_script( 'wp-color-picker');

			wp_enqueue_script(
				Better_Banners::PLUGIN_PREFIX . '_post_post_js',
				plugin_dir_url( __FILE__ ) . '../assets/post.js',
				array('jquery')
			);
		}
	}

	/**
	 * Register admin hooks with the registrar.
	 *
	 * @return void
	 */
	private function register_hooks() : void {
		$this->hook_registrar->register(
			'action',
			'init',
			new Init_Action_Handler
		);

		$this->hook_registrar->register(
			'action',
			'admin_menu',
			new Admin_Menu_Action_Handler
		);

		if ( self::is_current_page_banner_page() ) {
			$this->hook_registrar->register(
				'action',
				'in_admin_header',
				new In_Admin_Header_Action_Handler
			);
		}

		if ( self::is_current_page_banner_page() ) {
			$this->hook_registrar->register(
				'action',
				'in_admin_footer',
				new Plugin_In_Admin_Footer_Action_Handler
			);
		}

		if ( self::is_current_page_banner_post_page() ) {
			$this->hook_registrar->register(
				'action',
				'add_meta_boxes',
				new Post_Add_Meta_Boxes_Action_Handler
			);
		}

		if ( self::is_current_page_options_page() ) {
			$this->hook_registrar->register(
				'action',
				'init',
				new Options_Init_Action_Handler
			);
		}
	}

	/**
	 * Indicates whether the current page is an admin page associated with the
	 * plugin.
	 *
	 * @return bool
	 */
	private static function is_current_page_banner_page() : bool {
		$banner_post_type_slug = Better_Banners::get_banner_post_type_slug();

		return
			(
				isset( $_GET['post_type'] ) &&
				( $_GET['post_type'] === $banner_post_type_slug )
			) ||
			(
				isset( $_GET['post'] ) &&
				( get_post_type( $_GET['post'] ) === $banner_post_type_slug )
			);
	}

	/**
	 * Indicates whether the current page is the banner post page.
	 *
	 * @return bool
	 */
	public static function is_current_page_banner_post_page() : bool {
		global $pagenow;

		return ( $pagenow === 'post-new.php' || $pagenow === 'post.php') &&
			self::is_current_page_banner_page();
	}

	/**
	 * Indicates whether the current page is the banner table page.
	 *
	 * @return bool
	 */
	public static function is_current_page_banner_table_page() : bool {
		global $pagenow;

		return $pagenow === 'edit.php'  &&
			self::is_current_page_banner_page();
	}

	/**
	 * Indicates whether the current page is the options page.
	 *
	 * @return bool
	 */
	public static function is_current_page_options_page() : bool {
		return (
			self::is_current_page_banner_page() &&
			isset( $_GET['page'] ) &&
			$_GET['page'] === Better_Banners::PLUGIN_PREFIX . '_options'
		);
	}

	/**
	 * Returns the URL to the Better Banners logo image.
	 *
	 * @return string
	 */
	public static function get_logo_image_url() : string {
		return plugin_dir_url( __FILE__ ) . '../assets/better-banners-logo.svg';
	}
}