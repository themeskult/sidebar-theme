<?php
/**
 * sidebar functions and definitions
 *
 * @package sidebar
 * @since sidebar 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since sidebar 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'sidebar_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since sidebar 1.0
 */
function sidebar_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on sidebar, use a find and replace
	 * to change 'sidebar' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'sidebar', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'sidebar' ),
	) );


}
endif; // sidebar_setup
add_action( 'after_setup_theme', 'sidebar_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since sidebar 1.0
 */
function sidebar_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'sidebar' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'sidebar_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function sidebar_scripts() {

	$options = get_option( 'sidebar_options' ); 

	if ($options['droid_serif'] == 1) {
		wp_enqueue_style( 'fonts',  "http://fonts.googleapis.com/css?family=Droid+Serif:regular,regularitalic,bold,bolditalic");
	}
	
	// Stylesheets
	wp_enqueue_style( 'prettify',  get_template_directory_uri() . '/js/prettify/prettify.css');
	wp_enqueue_style( 'nanoScrollerJS',  get_template_directory_uri() . '/js/nanoScrollerJS/css/nanoscroller.css');
	wp_enqueue_style( 'tipsy-css',  get_template_directory_uri() . '/js/tipsy/stylesheets/tipsy.css');
	wp_enqueue_style( 'style', get_stylesheet_uri() );


	// Javascripts
	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	wp_deregister_script('jquery');
	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js', false, '1.8.2');
	wp_enqueue_script('jquery');

	wp_enqueue_script( 'prettify', get_template_directory_uri() . '/js/prettify/prettify.js', array( ), '20120206', true );

	wp_enqueue_script( 'nanoScrollerJS', get_template_directory_uri() . '/js/nanoScrollerJS/javascripts/jquery.nanoscroller.min.js', array( 'jquery' ), '20120206', true );

	wp_enqueue_script( 'history-js', get_template_directory_uri() . '/js/history.js', array( 'jquery' ), '20120206', true );

	wp_enqueue_script( 'scrollto-js', get_template_directory_uri() . '/js/jquery.scrollTo.min.js', array( 'jquery' ), '20120206', true );

	wp_enqueue_script( 'tipsy-js', get_template_directory_uri() . '/js/tipsy/javascripts/jquery.tipsy.js', array( 'jquery' ), null, true );

	wp_enqueue_script( 'site-js', get_template_directory_uri() . '/js/site.js', array( 'jquery' ), '20120206', true );

}
add_action( 'wp_enqueue_scripts', 'sidebar_scripts' );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );


add_action('admin_head', 'load_theme_scripts');


function load_theme_scripts() {
    wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic');
}

function estimated_reading_time($mycontent){
	$word = str_word_count(strip_tags($mycontent));
	$m = floor($word / 200);
	$s = floor($word % 200 / (200 / 60));
	$est = $m . ':' . ($m == 1 ? '' : 's') . ':' . $s . '' . ($s == 1 ? '' : 's');

	return $est;
}


function add_post_content($content) {

	if (is_single() or is_home()) {

	$content .= '
	<div class="clear">
		<div class="tk-sharing">

			<a href="" title="" class="tk-share-button">Share</a>
				
			<div class="tk-share-content">
				<i></i>
				<a class="tk-share-twitter" href="http://twitter.com/?status='.get_the_title().'%20'.wp_get_shortlink(get_the_ID()).'" onclick="window.open(this.href); return false;">TWITTER</a>

				<a href="http://www.facebook.com/sharer.php?u='.get_permalink().'&t='.get_the_title().'" onclick="window.open(this.href); return false;">FACEBOOK</a>

				<a href="http://reddit.com/submit?url='.get_permalink().'&amp;title='.get_the_title().'" onclick="window.open(this.href); return false;">REDDIT</a>

				<a href="mailto:?subject='.get_the_title().'&amp;body=Check out this article! '.get_permalink().'">EMAIL</a>
			</div>

		</div>
	</div>

	';

	}

	return $content;
}


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/inc/vendor/tgm-plugin-activation/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function my_theme_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name' 		=> 'Nav Menu Images',
			'slug' 		=> 'nav-menu-images',
			'required' 	=> true,
			'force_activation' 		=> true,
			'force_deactivation' 	=> true
		),

	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'tgmpa';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}

// unregister all default WP Widgets
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);


add_filter('the_content', 'add_post_content');

function get_estimated_time()
{
	global $post;
	$mycontent = $post->post_content; // wordpress users only
	$word = str_word_count(strip_tags($mycontent));
	$m = floor($word / 200);
	$s = floor($word % 200 / (200 / 60));
	$est = $m . ' minute' . ($m == 1 ? '' : 's') . " read";

	return $est;

}

require_once('inc/wp-updates-theme.php');
new WPUpdatesThemeUpdater( 'http://wp-updates.com/api/1/theme', 159, basename(get_template_directory()) );
