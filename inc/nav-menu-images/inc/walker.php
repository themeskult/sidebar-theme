<?php
/**
 * Nav Menu Images Nav Menu Edit Walker
 *
 * @package Nav Menu Images
 * @subpackage Nav Menu Edit Walker
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Filter nav menu items on edit screen.
 *
 * @since 1.0
 *
 * @uses Walker_Nav_Menu_Edit
 */
class NMI_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
	/**
	 * @see Walker_Nav_Menu_Edit::start_el()
	 * @since 1.0
	 * @access public
	 *
	 * @global $wp_version
	 * @uses Walker_Nav_Menu_Edit::start_el()
	 * @uses admin_url() To get URL of uploader.
	 * @uses esc_url() To escape URL.
	 * @uses add_query_arg() To append variables to URL.
	 * @uses esc_attr() To escape string.
	 * @uses has_post_thumbnail() To check if item has thumb.
	 * @uses get_the_post_thumbnail() To get item's thumb.
	 * @uses version_compare() To compare WordPress versions.
	 * @uses wp_create_nonce() To create item's nonce.
	 * @uses esc_html__() To translate & escape string.
	 * @uses esc_html() To escape string.
	 * @uses do_action_ref_array() Calls 'nmi_menu_item_walker_output' with the output.
	 *                        post object, depth and arguments to overwrite item's output.
	 * @uses NMI_Walker_Nav_Menu_Edit::get_settings() To get JSONed item's data.
	 * @uses do_action_ref_array() Calls 'nmi_menu_item_walker_end' with the output.
	 *                        post object, depth and arguments to overwrite item's output.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth, $args ) {
		global $wp_version;

		// First, make item with standard class
		parent::start_el( $output, $item, $depth, $args );

		// Now add additional content
		$item_id = $item->ID;

		// Form upload link
		$upload_url = admin_url( 'media-upload.php' );
		$query_args = array(
			'post_id'   => $item_id,
			'tab'       => 'gallery',
			'TB_iframe' => '1',
			'width'     => '640',
			'height'    => '425'
		);
		$upload_url = esc_url( add_query_arg( $query_args, $upload_url ) );

		// Hidden field with item's ID
		$output .= '<input type="hidden" name="nmi_item_id" id="nmi_item_id" value="' . esc_attr( $item_id ) . '" />';

		// Generate menu item image & link's text string
		if ( has_post_thumbnail( $item_id ) ) {
			$post_thumbnail = get_the_post_thumbnail( $item_id, 'thumb' );
			$output .= '<div class="nmi-current-image nmi-div" style="display: none;"><a href="' . $upload_url . '" data-id="' . $item_id . '" class="thickbox add_media">' . $post_thumbnail . '</a></div>';
			$link_text = __( 'Change menu item image', 'nmi' );

			// For WP 3.5+, add 'remove' action link
			if ( version_compare( $wp_version, '3.5', '>=' ) ) {
				$ajax_nonce = wp_create_nonce( 'set_post_thumbnail-' . $item_id );
				$remove_link = ' | <a href="#" data-id="' . $item_id . '" class="nmi_remove" onclick="NMIRemoveThumbnail(\'' . $ajax_nonce . '\',' . $item_id . ');return false;">' . esc_html__( 'Remove menu item image', 'nmi' ) . '</a>';
			}
		} else {
			$output .= '<div class="nmi-current-image nmi-div" style="display: none;"></div>';
			$link_text = __( 'Upload menu item image', 'nmi' );
		}

		// Append menu item upload link
		$output .= '<div class="nmi-upload-link nmi-div" style="display: none;"><a href="' . $upload_url . '" data-id="' . $item_id . '" class="thickbox add_media">' . esc_html( $link_text ) . '</a>';

		// Append menu item 'remove' link
		if ( isset( $remove_link ) )
			$output .= $remove_link;

		// Close menu item
		$output .= '</div>';

		// Filter output
		do_action_ref_array( 'nmi_menu_item_walker_output', array( &$output, $item, $depth, $args ) );

		// Add JSONed meta data
		$output .= $this->get_settings( $item_id );

		do_action_ref_array( 'nmi_menu_item_walker_end', array( &$output, $item, $depth, $args ) );
	}

	/**
	 * Get JSONed item's data.
	 *
	 * Heavily based on wp_enqueue_media() and
	 * WP_Scripts::localize()
	 *
	 * @see wp_enqueue_media()
	 * @see WP_Scripts::localize()
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @uses version_compare() To compare WordPress versions.
	 * @uses wp_create_nonce() To create item's nonce.
	 * @uses get_post() To get post's object.
	 * @uses get_post_meta() To get post's meta data.
	 * @uses apply_filters() Calls 'media_view_settings' with the settings
	 *                        and post object to overwrite item's settings.
	 * @uses did_action() To check if action was done.
	 * @uses do_action() Calls 'nmi_setup_settings_var' with the item ID.
	 *
	 * @param int $post_id The item's post ID.
	 * @return string New HTML output.
	 */
	public function get_settings( $post_id ) {
		global $wp_version;

		// Only works for WP 3.5+
		if ( ! version_compare( $wp_version, '3.5', '>=' ) )
			return;

		// Prepare general settings
		$settings = array();

		// Prepare post specific settings
		$post = null;
		if ( isset( $post_id ) ) {
			$post = get_post( $post_id );
			$settings['post'] = array(
				'id' => $post->ID,
				'nonce' => wp_create_nonce( 'update-post_' . $post->ID ),
			);

			$featured_image_id = get_post_meta( $post->ID, '_thumbnail_id', true );
			$settings['post']['featuredImageId'] = $featured_image_id ? $featured_image_id : -1;
			$settings['post']['featuredExisted'] = $featured_image_id ? 1 : -1;
		}

		// Filter item's settins
		$settings = apply_filters( 'media_view_settings', $settings, $post );

		// Prepare Javascript varible name
		$object_name = 'nmi_settings[' . $post->ID . ']';

		// Loop through each setting and prepare it for JSON
		foreach ( (array) $settings as $key => $value ) {
			if ( ! is_scalar( $value ) )
				continue;

			$settings[$key] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}

		// Encode settings to JSON
		$script = "$object_name = " . json_encode( $settings ) . ';';

		// If this is first item, register variable
		if ( ! did_action( 'nmi_setup_settings_var' ) ) {
			$script = "var nmi_settings = [];\n" . $script;
			do_action( 'nmi_setup_settings_var', $post->ID );
		}

		// Wrap everythig
		$output = "<script type='text/javascript'>\n"; // CDATA and type='text/javascript' is not needed for HTML 5
		$output .= "/* <![CDATA[ */\n";
		$output .= "$script\n";
		$output .= "/* ]]> */\n";
		$output .= "</script>\n";

		return $output;
	}
}