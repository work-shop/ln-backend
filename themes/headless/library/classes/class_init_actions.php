<?php

class WS_Init_Actions extends WS_Action_Set {

	/**
	 * Constructor
	 */
	public function __construct() {
		show_admin_bar(false);

		parent::__construct(
			array(
				'init' 					=> 'setup',
				'after_theme_setup'		=> array( 'remove_post_formats', 11, 0 ),
				'login_head'			=> 'login_css',
				'admin_head'			=> 'admin_css',
				'admin_menu'			=> 'all_settings_link',
				));
	}

	/** POST TYPES AND OTHER INIT ACTIONS */
	public function setup() {

		//add additional featured image sizes
		//NOTE: wordpress will allow hyphens in these names, but swig or the API(i'm not sure) will not
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'news', 512, 275, true );
			add_image_size( 'story', 400, 286, true );
			add_image_size( 'grid', 960, 720, true );
			add_image_size( 'hero', 1680, 1050, false );
		}

		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}


		//register post types
		//optional - include a custom icon, list of icons available at https://developer.wordpress.org/resource/dashicons/
		register_post_type( 'thoughts',
			array(
				'labels' => array(
					'name' => 'Thoughts',
					'singular_name' =>'Thought',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Thought',
					'edit_item' => 'Edit Thought',
					'new_item' => 'New Thought',
					'all_items' => 'All Thoughts',
					'view_item' => 'View Thoughts',
					'search_items' => 'Search Thoughts',
					'not_found' =>  'No Thoughts found ☹',
					'not_found_in_trash' => 'No Thoughts found in the Trash',
					),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'thoughts'),
				'show_in_rest'       => true,
				'rest_base'          => 'thoughts',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title', 'thumbnail'),
				'menu_icon'   => 'dashicons-format-status'
				));

        /** We'll use the informal notion of meta-data as a tool to discover what kinds of natural
         * taxonomies exist in lawrence's thinking. We'll*/
		// register_taxonomy(
		// 	'contexts',
		// 	array( 'thoughts' ),
		// 	array(
		// 		'hierarchical' => false,
		// 		'label' => 'Contexts',
		// 		'query_var' => true,
		// 		'rewrite' => array('slug' => 'contexts'),
		// 		'rest_base'          => 'contexts',
		// 		'rest_controller_class' => 'WP_REST_Terms_Controller',
		// 		)
		// 	);
        //
		// global $wp_taxonomies;
		// $taxonomy_name = 'contexts';
        //
		// if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
		// 	$wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
		// 	$wp_taxonomies[ $taxonomy_name ]->rest_base = $taxonomy_name;
		// 	$wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
		// }

		//add ACF options pages
		//optional - include a custom icon, list of icons available at https://developer.wordpress.org/resource/dashicons/
		if( function_exists('acf_add_options_page') ) {

			acf_add_options_page(array(
				'page_title' 	=> 'Home Page',
				'menu_title' 	=> 'Home Page',
				'menu_slug' 	=> 'home-page',
				'icon_url'      => 'dashicons-welcome-view-site',
				'position'		=> '50.1',
				));

		    acf_add_options_page(array(
				'page_title' 	=> 'About Page',
				'menu_title' 	=> 'About Page',
				'menu_slug' 	=> 'about-page',
				'icon_url'      => 'dashicons-welcome-widgets-menus',
				'position'		=> '50.3',
				));

		}


	}

	/* CUSTOM MENU LINK FOR ALL SETTINGS - WILL ONLY APPEAR FOR ADMIN */
	public function all_settings_link() {
		add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
	}

	/** ADMIN DASHBOARD ASSETS */
	public function login_css() { wp_enqueue_style( 'login_css', get_template_directory_uri() . '/assets/css/login.css' ); }
	public function admin_css() { wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/assets/css/admin.css' ); }

}

new WS_Init_Actions();
