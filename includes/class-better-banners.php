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
	public static string $default_banner_background_color = '#00c9c2';

	/**
	 * Returns the slug for the banner post type.
	 *
	 * @return string
	 */
	public static function get_banner_post_type_slug() : string {
		return self::$plugin_prefix . '_banner';
	}

	/**
	 * Returns the slug for the option to display banners using JavaScript.
	 *
	 * @return string
	 */
	public static function get_display_banners_using_javascript_option_slug() : string {
		return self::$plugin_prefix . '_display_banners_using_javascript';
	}

	/**
	 * Returns the slug for the option to add inline CSS for all banners.
	 *
	 * @return string
	 */
	public static function get_custom_inline_css_all_banners_option_slug() : string {
		return self::$plugin_prefix . '_custom_inline_css_all_banners';
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
			$is_current_page_banner_page = $_GET['post_type'] === self::get_banner_post_type_slug() ||
				get_post_type( $_GET['post'] ) === self::get_banner_post_type_slug();
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
		if ( ! get_option( self::get_display_banners_using_javascript_option_slug(), true ) ) {
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
			'edit.php?post_type=' . self::get_banner_post_type_slug(),
			'Better Banners Options',
			'Options',
			'manage_options',
			self::$plugin_prefix . '_options',
			function () {
				$plugin_prefix = self::$plugin_prefix;
				$checked = get_option( self::get_display_banners_using_javascript_option_slug() ) ? 'checked="checked"' : '';
				$checkbox_name = self::get_display_banners_using_javascript_option_slug();
				$textarea_name = self::get_custom_inline_css_all_banners_option_slug();
				$submit_button_name = self::$plugin_prefix . '_options_form_submit_button';
				$custom_inline_css_all_banners = get_option( self::get_custom_inline_css_all_banners_option_slug() );

				echo <<<HTML
<h1>Better Banners</h1>
<hr />
<h2>Options</h2>
<form method="post" action="{$_SERVER['REQUEST_URI']}" id="{$plugin_prefix}-options-form">
	<label for="{$checkbox_name}"><b>Display banners using JavaScript</b></label>
	<input type="checkbox" name="{$checkbox_name}" {$checked} />
	<br /><br />
	<span>
		<i>
			Some plugins or themes may prevent Better Banners from displaying properly.
			If your banners are not showing up, try toggling this option.
		</i>
	</span>
	<br /><br />
	<label for="{$textarea_name}"><b>Custom Inline CSS</b></label>
	<br />
	<textarea cols="40" rows="5" id="{$plugin_prefix}-custom-inline-css-all-banners" name="{$textarea_name}" form="{$plugin_prefix}-options-form">{$custom_inline_css_all_banners}</textarea>
	<br /><br />
	<label for="{$plugin_prefix}-custom-inline-css-all-banners-example"><b>Example</b></label>
	<br />
	<textarea cols="40" rows="5" id="{$plugin_prefix}-custom-inline-css-all-banners-example" disabled="disabled">color: red;\ntext-align: center;\nfont-weight: 700;</textarea>
	<br /><br />
	<span>
		<i>
			CSS declarations entered above will be applied to all banners.
		</i>
	</span>
	<br /><br />
	<button type="submit" name="{$submit_button_name}" />Save</button>
</form>
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
			isset( $_POST[ self::get_display_banners_using_javascript_option_slug() ] )
		) {
			update_option(
				self::get_display_banners_using_javascript_option_slug(),
				true
			);
		} elseif ( isset( $_POST[ self::$plugin_prefix . '_options_form_submit_button' ] ) ) {
			update_option(
				self::get_display_banners_using_javascript_option_slug(),
				false
			);
		}

		if (
			isset( $_POST[ self::$plugin_prefix . '_options_form_submit_button' ] ) &&
			isset( $_POST[ self::get_custom_inline_css_all_banners_option_slug() ] )
		) {
			update_option(
				self::get_custom_inline_css_all_banners_option_slug(),
				sanitize_option(
					self::get_custom_inline_css_all_banners_option_slug(),
					$_POST[ self::get_custom_inline_css_all_banners_option_slug() ]
				)
			);
		}

		if ( isset( $_POST['post_ID'] ) && isset( $_POST[ self::$plugin_prefix . '-custom-inline-css' ] ) ) {
			wp_update_post(
				array(
					'ID'         => intval( $_POST['post_ID'] ),
					'meta_input' => array(
						self::$plugin_prefix . '_custom_inline_css' => esc_html( $_POST[ self::$plugin_prefix . '-custom-inline-css' ] ),
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
		$option_name = self::get_display_banners_using_javascript_option_slug();

		if ( get_option( $option_name ) === false ) {
			add_option(
				$option_name,
				true
			);
		}

		$option_name = self::get_custom_inline_css_all_banners_option_slug();

		if ( get_option( $option_name ) === false ) {
			add_option(
				$option_name
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
			self::get_banner_post_type_slug(),
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
				'post_type'   => self::get_banner_post_type_slug(),
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

			$custom_inline_css = esc_attr(
				get_post_meta( $post->ID, self::$plugin_prefix . '_custom_inline_css' )[0]
			);

			$custom_inline_css_properties = explode(PHP_EOL, $custom_inline_css);

			foreach ($custom_inline_css_properties as &$custom_inline_css_property) {
				$custom_inline_css_property = rtrim(trim($custom_inline_css_property), ';');

				if ( $custom_inline_css_property ) {
					$custom_inline_css_property = rtrim(trim($custom_inline_css_property), ';') . ' !important;';
				}
			}

			$custom_inline_css_all_banners = esc_attr(
				get_option( self::get_custom_inline_css_all_banners_option_slug() )
			);

			$custom_inline_css_all_banners_properties = explode(PHP_EOL, $custom_inline_css_all_banners);

			foreach ($custom_inline_css_all_banners_properties as &$custom_inline_css_all_banners_property) {
				$custom_inline_css_all_banners_property = rtrim(trim($custom_inline_css_all_banners_property), ';');

				if ( $custom_inline_css_all_banners_property ) {
					$custom_inline_css_all_banners_property = rtrim(trim($custom_inline_css_all_banners_property), ';') . ' !important;';
				}
			}

			$style = 'background-color: ' . $background_color . ' !important;' . implode('', $custom_inline_css_properties) . implode('', $custom_inline_css_all_banners_properties);

			$html .= <<<HTML
<div class="{$plugin_prefix}-banner" style="{$style}" role="banner">
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

		$custom_inline_css = esc_html (
			get_post_meta( $post_id, $plugin_prefix . '_custom_inline_css' )[0]
		);

		echo <<<HTML
<div id="{$plugin_prefix}-color-picker-container">
	<label for="{$plugin_prefix}-background-color">Background Color</label>
	<input role="button" id="{$plugin_prefix}-background-color" class="{$plugin_prefix}-color-picker" type="text" value="{$background_color}" />
</div>
<br />
<div id="{$plugin_prefix}-custom-inline-css-container">
	<label for="{$plugin_prefix}-custom-inline-css">Custom Inline CSS</label>
	<br />
	<textarea rows="5" id="{$plugin_prefix}-custom-inline-css" name="{$plugin_prefix}-custom-inline-css" form="post">{$custom_inline_css}</textarea>
</div>
HTML;
	}

	/**
	 * Add the Settings meta boxes.
	 *
	 * @return void
	 */
	private function add_settings_meta_box() : void {
		$custom_post_type_slug = self::get_banner_post_type_slug();

		add_meta_box(
			"{$custom_post_type_slug}_meta_box",
			'Better Banners Settings',
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
				'displayBannersUsingJavaScript' => get_option( self::get_display_banners_using_javascript_option_slug(), true ),
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