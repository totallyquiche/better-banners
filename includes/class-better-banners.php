<?php declare(strict_types=1);

namespace TotallyQuiche\BetterBanners;

final class Better_Banners {
	/**
	 * The custom post type slug.
	 *
	 * @var string
	 */
	public static string $custom_post_type_slug = 'better_banners_post';

	/**
	 * The default background color for the banners.
	 *
	 * @var string
	 */
	private string $default_background_color = '81d742';

	/**
	 * Run the plugin.
	 *
	 * @return void
	 */
	public function run() : void {
		$this->init();
		$this->wp_enqueue_scripts();

		if (
			($post_type = $_GET['post_type']) === self::$custom_post_type_slug ||
			get_post_type( $_GET['post'] ) === self::$custom_post_type_slug
		) {
			$this->admin_enqueue_scripts();
		}

		$this->wp_body_open();
		$this->add_meta_boxes();
		$this->admin_post();
	}

	/**
	 * Add init actions.
	 *
	 * @return void
	 */
	private function init() : void {
		add_action(
			'init',
			array(
				$this,
				'register_post_type',
			)
		);
	}

	/**
	 * Add wp_body_open actions.
	 *
	 * @return void
	 */
	private function wp_body_open() : void {
		add_action(
			'wp_body_open',
			array(
				$this,
				'display_banners',
			)
		);
	}

	/**
	 * Display the banners.
	 *
	 * @return void
	 */
	public function display_banners() : void {
		$posts = get_posts(
			array(
				'post_type'   => self::$custom_post_type_slug,
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		echo '<div class="better-banners">';

		foreach ( $posts as $post ) {
			$background_color = esc_attr( get_post_meta( $post->ID, 'background_color' )[0] ?? $this->default_background_color );

			echo <<<HTML
<div class="better-banners-banner" style="background-color: #{$background_color};">
	{$post->post_content}
</div>
HTML;
		}

		echo '</div>';
	}

	/**
	 * Add the wp_enqueue_scripts actions.
	 *
	 * @return void
	 */
	private function wp_enqueue_scripts() : void {
		add_action(
			'wp_enqueue_scripts',
			array(
				$this,
				'enqueue_styles',
			)
		);
	}

	/**
	 * Enqueue the styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() : void {
		wp_enqueue_style(
			'better_banners_styles',
		   plugin_dir_url( __FILE__ ) . '../assets/css/public.css'
		);
	}

	/**
	 * Enqueue admin scripts/styles.
	 *
	 * @return void
	 */
	private function admin_enqueue_scripts() : void {
		add_action(
			'admin_enqueue_scripts',
			array(
				$this,
				'enqueue_admin_styles',
			)
		);

		add_action(
			'admin_enqueue_scripts',
			array(
				$this,
				'enqueue_admin_scripts',
			)
		);
	}

	/**
	 * Enqueue admin styles.
	 *
	 * @return void
	 */
	public function enqueue_admin_styles() : void {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'better_banners_admin_css',
			plugin_dir_url( __FILE__ ) . '../assets/css/admin.css'
		);
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() : void {
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script(
			'better_banners_admin_js',
			plugin_dir_url( __FILE__ ) . '../assets/js/admin.js',
			array('iris')
		);
	}

	/**
	 * Register the custom post type.
	 *
	 * @return void
	 */
	public function register_post_type() : void {
		register_post_type(
			self::$custom_post_type_slug,
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
					'filter_items_list'      => 'Filter Better Banners list',
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
	 * Add add_meta_boxes action.
	 *
	 * @return void
	 */
	private function add_meta_boxes() : void {
		add_action(
			'add_meta_boxes',
			array(
				$this,
				'add_meta_box',
			)
		);
	}

	/**
	 * Add all meta boxes.
	 *
	 * @return void
	 */
	public function add_meta_box() : void {
		$custom_post_type_slug = self::$custom_post_type_slug;
		add_meta_box(
			"{$custom_post_type_slug}_meta_box",
			'Settings',
			array(
				$this,
				'render_meta_box',
            ),
			$custom_post_type_slug
		);
	}

	/**
	 * Render the Settings meta box on the custom post type page.
	 *
	 * @return void
	 */
	public function render_meta_box() : void {
		$post_meta_input = get_post_meta( get_the_ID() );
		$background_color = esc_attr( ( $post_meta_input['background_color'][0] ?? $this->default_background_color ) );

		echo <<<HTML
<div id="color-picker-container">
	<span>Background Color</span>
	<br />
	<input id="background-color" class="color-picker" type="text" value="#{$background_color}" />
</div>
HTML;
	}

	/**
	 * Handle admin POST requests.
	 *
	 * @return void
	 */
	private function admin_post() : void {
		if ( isset( $_POST['post_ID'] ) && isset( $_POST['background-color'] ) ) {
			wp_update_post(
				array(
					'ID'         => $_POST['post_ID'],
					'meta_input' => array('background_color' => $_POST['background-color']),
				)
			);
		}
	}
}