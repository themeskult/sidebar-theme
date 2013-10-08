<?php

global $options, $redirect_url;

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

$options = get_option( 'sidebar_options' ); 


function theme_options_init(){
	register_setting( 'sample_options', 'sidebar_options' );
}

function theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'wordpress-sidebar' ), __( 'Theme Options', 'wordpress-sidebar' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}


function theme_options_do_page() {
	global $select_options, $radio_options;


	$options = get_option( 'sidebar_options' ); 

	// print_r($options);

	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$('#color_picker_color1').farbtastic('#color1');            
		});
	</script>
	
	<div class="wrap">

		<?php screen_icon(); echo "<h2>" . wp_get_theme() .' ' . __( 'Options', 'wordpress-sidebar' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'wordpress-sidebar' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'sample_options' ); ?>
			<?php 
				  if( ! is_null( $options['color'] ) && '' != $options['color'] )
				  	$color = esc_attr( $options['color'] );
				  else
				  	$color = '#20d6ab';
			?>

				<table class="form-table">
		
					<tr>
						<th><?php _e( 'Theme color', 'wordpress-sidebar' ); ?></th>
						<td>
							<input id="color1" class="regular-text" type="text" name="sidebar_options[color]" value="<?php echo $color; ?>" />
							<div id="color_picker_color1"></div>
						</td>
					</tr>

					<tr>
						<th><?php _e( 'Load Droid Serif font from Google Fonts', 'wordpress-sidebar' ); ?></th>
						<td>
							<input id="droid_serif" class="regular-text" type="checkbox" name="sidebar_options[droid_serif]" value="1" 
							<?php if ($options['droid_serif'] == 1): ?>
								checked
							<?php endif ?>
							/>
						</td>
					</tr>

					<tr>
						<th><?php _e( 'Custom code', 'wordpress-sidebar' ); ?></th>
						<td><textarea id="sidebar_options[google_analytics]" class="small-text" cols="50" rows="10" name="sidebar_options[google_analytics]"><?php echo esc_textarea( $options['google_analytics'] ); ?></textarea></td>
					</tr>
				</tbody>
			</table>
				
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'wordpress-sidebar' ); ?>" />
			</p>

		</form>
	</div>
	<?php
}

function theme_options_validate( $input ) {

	global $select_options, $radio_options;

	$input['color'] = wp_filter_nohtml_kses( $input['color'] );

	$input['droid_serif'] = wp_filter_nohtml_kses( $input['droid_serif'] );

	$input['google_analytics'] = $input['google_analytics'] ;

	return $input;
}

?>