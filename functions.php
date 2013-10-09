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
	wp_enqueue_style( 'normalize', '//normalize-css.googlecode.com/svn/trunk/normalize.css');
	wp_enqueue_style( 'prettify',  '//google-code-prettify.googlecode.com/svn/trunk/src/prettify.css');
	wp_enqueue_style( 'nanoScrollerJS',  get_template_directory_uri() . '/js/nanoScrollerJS/css/nanoscroller.css');
	wp_enqueue_style( 'tipsy-css',  get_template_directory_uri() . '/js/tipsy/stylesheets/tipsy.css');
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	// Javascripts
	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	wp_deregister_script('jquery');
	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js', false, '1.8.2');
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'prettify', '//google-code-prettify.googlecode.com/svn/trunk/src/prettify.js', array( ), '20120206', true );
	wp_enqueue_script( 'nanoScrollerJS', get_template_directory_uri() . '/js/nanoScrollerJS/javascripts/jquery.nanoscroller.min.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'history-js', get_template_directory_uri() . '/js/history.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'scrollto-js', get_template_directory_uri() . '/js/jquery.scrollTo.min.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'tipsy-js', get_template_directory_uri() . '/js/tipsy/javascripts/jquery.tipsy.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'site-js', get_template_directory_uri() . '/js/site.js', array( 'jquery' ), '20120206', true );

}
add_action( 'wp_enqueue_scripts', 'sidebar_scripts' );

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
// add_action( 'wp_head', 'of_stylesheet' );

// Re-define the options-framework URL
define( 'OPTIONS_FRAMEWORK_URL', get_template_directory_uri() . '/inc/options-framework/' );

// Load the Options Framework Plugin
if ( !function_exists( 'optionsframework_init' ) ) {
    define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory() . '/inc/options-framework/' );
    require_once OPTIONS_FRAMEWORK_DIRECTORY . 'options-framework.php';
}

require get_template_directory() . '/inc/Theme-Updater/updater.php';
require get_template_directory() . '/inc/nav-menu-images/nav-menu-images.php';






