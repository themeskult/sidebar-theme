<?php
/**
 * Nav Menu Images Admin Functions
 *
 * @package Nav Menu Images
 * @subpackage Admin Functions
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Nav Menu Images admin functions.
 *
 * @since 1.0
 *
 * @uses Nav_Menu_Images
 */
class Nav_Menu_Images_Admin extends Nav_Menu_Images {
	/**
	 * Sets class properties.
	 * 
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_filter() To hook filters.
	 * @uses add_action() To hook functions.
	 */
	public function __construct() {
		// Register new AJAX thumbnail response
		add_filter( 'admin_post_thumbnail_html', array( &$this, '_wp_post_thumbnail_html'   ), 10, 2 );

		// Register walker replacement
		add_filter( 'wp_edit_nav_menu_walker',   array( &$this, 'filter_walker'             )        );

		// Register enqueuing of scripts
		add_action( 'admin_menu',                array( &$this, 'register_enqueuing'        )        );

		// Register attachment fields display
		add_filter( 'attachment_fields_to_edit', array( &$this, 'attachment_fields_to_edit' ), 10, 2 );

		// Register attachment fields handling
		add_filter( 'attachment_fields_to_save', array( &$this, 'attachment_fields_to_save' ), 10, 2 );
	}

	/**
	 * Register script enqueuing on nav menu page.
	 * 
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_action() To hook function.
	 */
	public function register_enqueuing() {
		add_action( 'admin_print_scripts-nav-menus.php', array( &$this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue necessary scripts.
	 * 
	 * @since 1.0
	 * @access public
	 *
	 * @global $wp_version.
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses wp_enqueue_script() To enqueue scripts.
	 * @uses plugins_url() To get URL of the file.
	 * @uses wp_localize_script() To add script's variables.
	 * @uses add_thickbox() To enqueue Thickbox style & script.
	 * @uses version_compare() To compare WordPress versions.
	 * @uses wp_enqueue_media() To load media view templates, scripts & styles.
	 * @uses do_action() Calls 'nmi_enqueue_scripts'.
	 */
	public function enqueue_scripts() {
		global $wp_version;

		// Load translations
		$this->load_textdomain();

		// Enqueue old script
		wp_enqueue_script( 'nmi-scripts', plugins_url( 'nmi.js', __FILE__ ), array( 'media-upload', 'thickbox' ), '1', true );
		wp_localize_script( 'nmi-scripts', 'nmi_vars', array(
				'alert' => __( 'You need to set an image as a featured image to be able to use it as an menu item image', 'nmi' )
			)
		);
		add_thickbox();

		// For WP 3.5+, enqueue new script & dependicies
		if ( version_compare( $wp_version, '3.5', '>=' ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'nmi-media-view', plugins_url( 'media-view.js', __FILE__ ), array( 'jquery', 'media-editor', 'media-views', 'post' ), '1.1', true );
		}

		do_action( 'nmi_enqueue_scripts' );
	}

	/**
	 * Use custom walker for nav menu edit.
	 * 
	 * @since 1.0
	 * @access public
	 *
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 *
	 * @param string $walker Name of used walker class.
	 */
	public function filter_walker( $walker ) {
		// Load translations
		$this->load_textdomain();

		require_once dirname( __FILE__ ) . '/walker.php';
		return 'NMI_Walker_Nav_Menu_Edit';
	}

	/**
	 * Output HTML for the post thumbnail meta-box.
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @uses get_post() To get post's object.
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses admin_url() To get URL of uploader.
	 * @uses esc_url() To escape URL.
	 * @uses add_query_arg() To append variables to URL.
	 * @uses has_post_thumbnail() To check if item has thumb.
	 * @uses get_the_post_thumbnail() To get item's thumb.
	 * @uses wp_create_nonce() To create item's nonce.
	 * @uses esc_html__() To translate & escape string.
	 * @uses apply_filters() Calls 'nmi_admin_post_thumbnail_html' to
	 *                        overwrite returned output.
	 *
	 * @param string $content Original HTML output of the thubnail.
	 * @param int $post_id The post ID associated with the thumbnail.
	 * @return string New HTML output.
	 */
	public function _wp_post_thumbnail_html( $content, $post_id ) {
		// Check if request from this plugin
		if ( ! isset( $_REQUEST['nmi_request'] ) )
			return $content;

		// Get post object
		$post = get_post( $post_id );

		// Check if post exists and is nav menu item
		if ( ! $post || 'nav_menu_item' != $post->post_type )
			return $content;

		// Load translations
		$this->load_textdomain();

		// Form upload link
		$upload_url = admin_url( 'media-upload.php' );
		$query_args = array(
			'post_id'   => $post->ID,
			'tab'       => 'gallery',
			'TB_iframe' => '1',
			'width'     => '640',
			'height'    => '425'
		);
		$upload_url = esc_url( add_query_arg( $query_args, $upload_url ) );

		// Item's featured image or plain link
		if ( has_post_thumbnail( $post->ID ) )
			$link = get_the_post_thumbnail( $post->ID, 'thumb' );
		else
			$link = __( 'Upload menu item image', 'nmi' );

		// Full link
		$content = '<a href="' . $upload_url . '" data-id="' . $post->ID . '" class="thickbox add_media">' . $link . '</a>';

		// If item didn't have image, prepend actions links
		if ( isset( $_REQUEST['thumb_was'] ) && -1 == $_REQUEST['thumb_was'] ) {
			$link_text = __( 'Change menu item image', 'nmi' );
			$ajax_nonce = wp_create_nonce( 'set_post_thumbnail-' . $post->ID );
			$remove_link = ' | <a href="#" data-id="' . $post->ID . '" class="nmi_remove" onclick="NMIRemoveThumbnail(\'' . $ajax_nonce . '\',' . $post->ID . ');return false;">' . esc_html__( 'Remove menu item image', 'nmi' ) . '</a>';

			$actions = '<a href="' . $upload_url . '" data-id="' . $post->ID . '" class="thickbox add_media">' . $link_text . '</a>' . $remove_link;

			$content = $actions . $content;
		}

		// Filter returned HTML output
		return apply_filters( 'nmi_admin_post_thumbnail_html', $content, $post->ID );
	}

	/**
	 * Display hover & active image checkboxes.
	 *
	 * @since 3.0
	 * @access public
	 *
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses get_post_meta() To get item's hover & active images IDs.
	 * @uses checked() To set proper checkbox status.
	 * @uses apply_filters() Calls 'nmi_attachment_fields_to_edit' to
	 *                        overwrite returned form fields.
	 *
	 * @param array $form_fields Original attachment form fields.
	 * @param object $post The post object data of attachment.
	 * @return string New attachment form fields.
	 */
	function attachment_fields_to_edit( $form_fields, $post ) {
		// Display only for images
		if ( ! wp_attachment_is_image( $post->ID ) )
			return $form_fields;

		// Display only for nav menu items
		if ( 'nav_menu_item' != get_post_type( $post->post_parent ) )
			return $form_fields;

		// Load translations
		$this->load_textdomain();

		// Add "hover" checkbox
		$parent_hover_id = get_post_meta( $post->post_parent, '_nmi_hover', true );
		$is_hover = ( $parent_hover_id == $post->ID ) ? true : false;
		$is_hover_checked = checked( $is_hover, true, false );

		$form_fields['nmihover'] = array(
			'label'        => __( 'Used on hover?', 'nmi' ),
			'input'        => 'html',
			'html'         => "<input type='checkbox' class='nmi-hover-checkbox' {$is_hover_checked} name='attachments[{$post->ID}][nmihover]' id='attachments[{$post->ID}][nmihover]' data-parent='{$post->post_parent}' data-checked='{$is_hover}' />",
			'value'        => $is_hover,
			'helps'        => __( 'Should this image be used on hover', 'nmi' ),
			'show_in_edit' => false
		);

		// Add "active" checkbox
		$parent_active_id = get_post_meta( $post->post_parent, '_nmi_active', true );
		$is_active = ( $parent_active_id == $post->ID ) ? true : false;
		$is_active_checked = checked( $is_active, true, false );

		$form_fields['nmiactive'] = array(
			'label'        => __( 'Used when active?', 'nmi' ),
			'input'        => 'html',
			'html'         => "<input type='checkbox' class='nmi-active-checkbox' {$is_active_checked} name='attachments[{$post->ID}][nmiactive]' id='attachments[{$post->ID}][nmiactive]' data-parent='{$post->post_parent}' data-checked='{$is_active}' />",
			'value'        => $is_active,
			'helps'        => __( 'Should this image be used when menu item is active', 'nmi' ),
			'show_in_edit' => false
		);

		// Filter returned HTML output
		return apply_filters( 'nmi_attachment_fields_to_edit', $form_fields, $post );
	}

	/**
	 * Save hover & active image checkboxes submissions.
	 *
	 * @since 3.0
	 * @access public
	 *
	 * @uses update_post_meta() To save new item's hover or active images IDs.
	 * @uses delete_post_meta() To delete old item's hover or active images IDs.
	 *
	 * @param object $post The post object data of attachment.
	 * @param array $attachment Submitted data of attachment.
	 * @return object $post The post object data of attachment.
	 */
	function attachment_fields_to_save( $post, $attachment ) {
		// Save "hover" checkbox
		if ( 'on' == $attachment['nmihover'] )
			update_post_meta( $post['post_parent'], '_nmi_hover', $post['ID'] );
		else
			delete_post_meta( $post['post_parent'], '_nmi_hover', $post['ID'] );

		// Save "active" checkbox
		if ( 'on' == $attachment['nmiactive'] )
			update_post_meta( $post['post_parent'], '_nmi_active', $post['ID'] );
		else
			delete_post_meta( $post['post_parent'], '_nmi_active', $post['ID'] );

		return $post;  
	}
}
