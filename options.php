<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);

	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {

	// Test data
	$test_array = array(
		'one' => __('One', 'options_check'),
		'two' => __('Two', 'options_check'),
		'three' => __('Three', 'options_check'),
		'four' => __('Four', 'options_check'),
		'five' => __('Five', 'options_check')
	);

	// Multicheck Array
	$multicheck_array = array(
		'one' => __('French Toast', 'options_check'),
		'two' => __('Pancake', 'options_check'),
		'three' => __('Omelette', 'options_check'),
		'four' => __('Crepe', 'options_check'),
		'five' => __('Waffle', 'options_check')
	);

	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '#fff',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );
		
	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	$options = array();

	$options[] = array(
		'name' => __('Basic Settings', 'options_check'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Site Color', 'options_check'),
		'desc' => __('No color selected by default.', 'options_check'),
		'id' => 'site_color',
		'std' => '#feb83e',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Titles Color', 'options_check'),
		'desc' => __('No color selected by default.', 'options_check'),
		'id' => 'titles_color',
		'std' => '#000',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Text Color', 'options_check'),
		'desc' => __('No color selected by default.', 'options_check'),
		'id' => 'text_color',
		'std' => '#4d4d4d',
		'type' => 'color' );

	$options[] = array(
		'name' =>  __('Background', 'options_check'),
		'desc' => __('Change the background CSS.', 'options_check'),
		'id' => 'background',
		'std' => $background_defaults,
		'type' => 'background' );

	$options[] = array(
		'name' => __('Header Image', 'options_check'),
		'desc' => __('This creates a full size uploader that previews the image.', 'options_check'),
		'id' => 'header_background_image',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Navigation Background Color', 'options_check'),
		'desc' => __('No color selected by default.', 'options_check'),
		'id' => 'navigation_background_color',
		'std' => '#f1f1f1',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Navigation Text Color', 'options_check'),
		'desc' => __('No color selected by default.', 'options_check'),
		'id' => 'navigation_text_color',
		'std' => '#333',
		'type' => 'color' );


	$options[] = array(
		'name' => __('Footer Text', 'options_check'),
		'id' => 'footer_text',
		'std' => 'Footer Text',
		'type' => 'text');
	
	return $options;
}