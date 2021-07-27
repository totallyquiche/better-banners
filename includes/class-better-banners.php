<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners;

final class Better_Banners {
	/**
	 * The universal prefix for this plugin.
	 *
	 * @var string
	 */
	public static string $plugin_prefix = 'tqbb01';

	/**
	 * The default background color for banners.
	 *
	 * @var string
	 */
	public static string $default_banner_background_color = '#007bff';

	/**
	 * Returns the slug for the banner post type.
	 *
	 * @return string
	 */
	public static function getBannerPostTypeSlug() : string {
		return self::$plugin_prefix . '_banner';
	}

	/**
	 * Returns the slug for the option to display banners using JavaScript.
	 *
	 * @return string
	 */
	public static function getDisplayBannersUsingJavaScriptOptionSlug() : string {
        return self::$plugin_prefix . '_display_banners_using_javascript';
	}

	/**
	 * Run the plugin.
	 *
	 * @return void
	 */
	public function run() : void {
		$actions = array(
			'init',
			'wp_body_open',
			'wp_enqueue_scripts',
			'admin_enqueue_scripts',
			'add_meta_boxes',
			'in_admin_footer',
			'admin_menu',
			'admin_post',
		);

		foreach ( $actions as $action ) {
			add_action(
				$action,
				array(
					$this,
					$action
				)
			);
		}
	}

	/**
	 * Returns a boolean indicating whether the current page is a banner post page.
	 *
	 * @return bool
	 */
	private function isCurrentPageBannerPage() : bool {
		static $is_current_page_banner_page;

		if ( ! isset( $is_current_page_banner_page ) ) {

			$is_current_page_banner_page = $_GET['post_type'] === self::getBannerPostTypeSlug() ||
				get_post_type( $_GET['post'] ) === self::getBannerPostTypeSlug();
		}

		return $is_current_page_banner_page;
	}

	/**
	 * Handle the init action.
	 *
	 * @return void
	 */
	public function init() : void {
		$this->add_options();
		$this->register_post_type();
		$this->handle_post();
	}

	/**
	 * Handle the wp_body_open action.
	 *
	 * @return void
	 */
	public function wp_body_open() : void {
		if ( ! get_option( self::getDisplayBannersUsingJavaScriptOptionSlug(), true ) ) {
			$this->display_banners();
		}
	}

	/**
	 * Handle the wp_enqueue_scripts action.
	 *
	 * @return void
	 */
	public function wp_enqueue_scripts() : void {
		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	/**
	 * Handle the admin_enqueue_scripts action.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() : void {
		if ( $this->isCurrentPageBannerPage() ) {
			$this->enqueue_admin_styles();
			$this->enqueue_admin_scripts();
		}
	}

	/**
	 * Handle the add_meta_boxes action.
	 *
	 * @return void
	 */
	public function add_meta_boxes() : void {
		if ( $this->isCurrentPageBannerPage() ) {
			$this->add_settings_meta_box();
		}
	}

	/**
	 * Handle the in_admin_footer action.
	 *
	 * @return void
	 */
	public function in_admin_footer() : void {
		if ( $this->isCurrentPageBannerPage() ) {
			$this->render_admin_footer();
		}
	}

	/**
	 * Handle the admin_menu action.
	 *
	 * @return void
	 */
	public function admin_menu() : void {
        add_submenu_page(
            'edit.php?post_type=' . self::getBannerPostTypeSlug(),
            'Better Banners Options',
            'Options',
            'manage_options',
            self::$plugin_prefix . '_options',
            function () {
				$plugin_prefix = self::$plugin_prefix;
				$checked = get_option( self::getDisplayBannersUsingJavaScriptOptionSlug() ) ? 'checked="checked"' : '';
				$checkbox_name = self::getDisplayBannersUsingJavaScriptOptionSlug();
				$submit_button_name = self::$plugin_prefix . '_options_form_submit_button';

				echo <<<HTML
<h1>Better Banners</h1>
<hr />
<h2>Options</h2>
<form method="post" action="{$_SERVER['REQUEST_URI']}" id="{$plugin_prefix}-options-form">
	<label for="{$checkbox_name}">Display banners using JavaScript</label>
	<input type="checkbox" name="{$checkbox_name}" {$checked} />
	<button type="submit" name="{$submit_button_name}" />Save</button>
</form>
<br />
<span>
	<i>
		Some plugins or themes may prevent Better Banners from displaying properly.
		If your banners are not showing up, try toggling this option.
	</i>
</span>
HTML;
			}
        );
	}

	/**
	 * Handle form posts.
	 *
	 * @return void
	 */
	private function handle_post() : void {
		if ( isset( $_POST['post_ID'] ) && isset( $_POST[self::$plugin_prefix . '-background-color'] ) ) {
			wp_update_post(
				array(
					'ID'         => intval( $_POST['post_ID'] ),
					'meta_input' => array(
						self::$plugin_prefix . '_background_color' => sanitize_hex_color( $_POST[self::$plugin_prefix . '-background-color'] ),
					),
				)
			);
		}

		if (
			isset( $_POST[ self::$plugin_prefix . '_options_form_submit_button' ] ) &&
			isset( $_POST[ self::getDisplayBannersUsingJavaScriptOptionSlug() ] )
		) {
			update_option(
				self::getDisplayBannersUsingJavaScriptOptionSlug(),
				true
			);
		} elseif ( isset( $_POST[ self::$plugin_prefix . '_options_form_submit_button' ] ) ) {
			update_option(
				self::getDisplayBannersUsingJavaScriptOptionSlug(),
				false
			);
		}

		if ( isset( $_POST['post_ID'] ) && isset( $_POST[ self::$plugin_prefix . '-custom-css' ] ) ) {
			wp_update_post(
				array(
					'ID'         => intval( $_POST['post_ID'] ),
					'meta_input' => array(
						self::$plugin_prefix . '_custom_css' => esc_html( $_POST[ self::$plugin_prefix . '-custom-css' ] ),
					),
				)
			);
		}
	}

	/**
	 * Add all options.
	 *
	 * @return void
	 */
	private function add_options() : void {
		$option_name = self::getDisplayBannersUsingJavaScriptOptionSlug();

		if ( get_option( $option_name ) === false ) {
			add_option(
				$option_name,
				true
			);
		}
	}

	/**
	 * Register the custom post type.
	 *
	 * @return void
	 */
	private function register_post_type() : void {
		register_post_type(
			self::getBannerPostTypeSlug(),
			array(
				'description' => 'A Better Banners banner.',
				'public'      => true,
				'menu_icon'   => 'dashicons-megaphone',
				'rewrite'     => false,
				'labels'      => array(
					'name'                  => 'Better Banners',
					'singular_name'         => 'Better Banner',
					'add_new_item'          => 'Add New Better Banner',
					'edit_item'             => 'Edit Better Banner',
					'new_item'              => 'New Better Banner',
					'view_item'             => 'View Better Banner',
					'view_items'            => 'View Better Banners',
					'search_items'          => 'Search Better Banners',
					'not_found'             => 'No Better Banners found',
					'not_found_in_trash'    => 'No Better Banners found in Trash',
					'all_items'             => 'All Better Banners',
					'insert_into_item'      => 'Insert into Better Banner',
					'uploaded_to_this_item' => 'Uploaded to this Better Banner',
					'filter_items_list'     => 'Filter Better Banners list',
					'items_list_navigation' => 'Better Banners list navigation',
					'items_list'            => 'Better Banners list',
					'item_published'        => 'Better Banner published',
					'item_updated'          => 'Better Banner updated',
					'item_link'             => 'Better Banner Link',
					'item_link_description' => 'A link to a Better Banner',
				),
			)
		);
	}

	/**
	 * Render the admin footer content.
	 *
	 * @return void
	 */
	private function render_admin_footer() : void {
		$message = <<<HTML
<span>
	Betters Banners was created with love by <a href="https://briandady.com" target="_BLANK" title="Brian Dady's website">Brian Dady</a>.
	&#129505;
</span>

<hr />
HTML;

		echo apply_filters( 'render_admin_footer_message', $message );
	}

	/**
	 * Return the banners HTML.
	 *
	 * @return string
	 */
	private function get_banners_html() : string {
		$posts = get_posts(
			array(
				'post_type'   => self::getBannerPostTypeSlug(),
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		$plugin_prefix = self::$plugin_prefix;

		$posts = apply_filters(
			'better_banners_display_banners_posts',
			$posts
		);

		$html = '<div class="' . $plugin_prefix . '-banners-container">';

		foreach ( $posts as $post ) {
			$background_color = esc_attr(
				get_post_meta( $post->ID, self::$plugin_prefix . '_background_color' )[0] ?? self::$default_banner_background_color
			);

			$html .= <<<HTML
<div class="{$plugin_prefix}-banner" style="background-color: {$background_color};" role="banner">
	{$post->post_content}
</div>
HTML;
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Display the banners via wp_body_open.
	 *
	 * @return void
	 */
	private function display_banners() : void {
		echo $this->get_banners_html();
	}

	/**
	 * Render the Settings meta box on the custom post type page.
	 *
	 * @return void
	 */
	public function render_settings_meta_box() : void {
		$post_id = get_post()->ID;
        $plugin_prefix = self::$plugin_prefix;

		$background_color = esc_attr(
			get_post_meta( $post_id, $plugin_prefix . '_background_color' )[0] ?? self::$default_banner_background_color
		);

		$custom_css = esc_html (
			get_post_meta( $post_id, $plugin_prefix . '_custom_css' )[0]
		);

		echo <<<HTML
<div id="{$plugin_prefix}-color-picker-container">
	<label for="{$plugin_prefix}-background-color">Background Color</label>
	<input role="button" id="{$plugin_prefix}-background-color" class="{$plugin_prefix}-color-picker" type="text" value="{$background_color}" />
</div>
<br />
<div id="{$plugin_prefix}-custom-css-container">
	<label for="{$plugin_prefix}-custom-css">Custom CSS</label>
	<br />
	<textarea rows="5" id="{$plugin_prefix}-custom-css" name="{$plugin_prefix}-custom-css" form="post">{$custom_css}</textarea>
</div>
HTML;
	}

	/**
	 * Add the Settings meta boxes.
	 *
	 * @return void
	 */
	private function add_settings_meta_box() : void {
		$custom_post_type_slug = self::getBannerPostTypeSlug();

		add_meta_box(
			"{$custom_post_type_slug}_meta_box",
			'Settings',
			array(
				$this,
				'render_settings_meta_box',
			),
			$custom_post_type_slug
		);
	}

	/**
	 * Enqueue the admin scripts.
	 *
	 * @return void
	 */
	private function enqueue_admin_scripts() : void {
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script(
			'better_banners_admin_js',
			plugin_dir_url( __FILE__ ) . '../assets/js/admin.js',
			array(
				'jquery',
				'iris',
			)
		);
	}

	/**
	 * Enqueue the public styles.
	 *
	 * @return void
	 */
	private function enqueue_styles() : void {
		wp_enqueue_style(
			'better_banners_styles',
			plugin_dir_url( __FILE__ ) . '../assets/css/public.css'
		);
	}

	/**
	 * Enqueue the public scripts.
	 *
	 * @return void
	 */
	private function enqueue_scripts() : void {
		wp_enqueue_script(
			'better_banners_js',
			plugin_dir_url( __FILE__ ) . '../assets/js/public.js',
			array( 'jquery' )
		);

		wp_localize_script(
			'better_banners_js',
			'localizations',
			array(
				'displayBannersUsingJavaScript' => get_option( self::getDisplayBannersUsingJavaScriptOptionSlug(), true ),
				'bannersHtml' => $this->get_banners_html(),
			)
		);
	}

	/**
	 * Enqueue the admin styles.
	 *
	 * @return void
	 */
	private function enqueue_admin_styles() : void {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'better_banners_admin_css',
			plugin_dir_url( __FILE__ ) . '../assets/css/admin.css'
		);
	}
}