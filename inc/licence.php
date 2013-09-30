<?php
global $options, $redirect_url;

add_action( 'admin_init', 'licence_init' );
add_action( 'admin_menu', 'licence_add_page' );

function licence_init(){
	register_setting( 'sample_options', 'symbol_options' );
}

function licence_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_licence', 'licence', 'licence_do_page' );
}


function licence_do_page() {
	global $select_options, $radio_options;

	$themename = strtolower(get_current_theme());
	$current_user = wp_get_current_user();

	if ($_GET['enter_licence']) {
		$enter_licence = 1;

		if (!empty($_POST['licence-name']) and !empty($_POST['licence-email']) and !empty($_POST['licence-key'])) {
			//set POST variables
			$url = 'http://themeskult.com/wp-content/themes/tk2/validate.php';
			$fields = array(
									'licence-email' => $_POST['licence-email'],
									'licence-name' => $_POST['licence-name'],
									'licence-key' => $_POST['licence-key']
							);

			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');

			//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


			//execute post

			$result = json_decode(curl_exec($ch));

			if ($result->status == 1) {
				add_option('licence_' . $themename, 1);
			}		        

			wp_redirect(admin_url('/themes.php?page=options-framework', 'http'), 301);


			//close connection
			curl_close($ch);

		}

	}else{
		$enter_licence = false;
	}

	?>
	
	<div class="wrap">

		<?php screen_icon();?>
		<h2 class="nav-tab-wrapper" style="margin-bottom: 20px;">
	        <a id="options-group-1-tab" class="nav-tab basicsettings-tab <?php if (!$enter_licence): ?> nav-tab-active <?php endif ?>" title="Basic Settings" href="themes.php?page=licence">Buy Licence</a>    
	        <a id="options-group-1-tab" class="nav-tab basicsettings-tab <?php if ($enter_licence): ?> nav-tab-active <?php endif ?>" title="Basic Settings" href="themes.php?page=licence&enter_licence=1">Enter the licence</a>    
        </h2>

        <?php if (!$enter_licence): ?>
			<iframe src="http://themeskult.com/store/" width="100%" height="550" ></iframe>        	
        <?php endif ?>

		<?php if ($enter_licence): ?>
			
		<form method="post" action="themes.php?page=licence&enter_licence=1&noheader=true">

			<?php if ($error): ?>
				<p style='color: red; font-weight: bold;'><?php print_r($error) ?></p>
			<?php endif ?>

			<table class="form-table">
				
				<input class="regular-text" type="hidden" name="licence-name" value="<?php echo $themename; ?>" />

				<tr>
					<th><?php _e( 'Email Address'); ?></th>
					<td>
						<input class="regular-text" type="text" name="licence-email" value="<?php echo $current_user->user_email; ?>" />
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Licence Key'); ?></th>
					<td>
						<input class="regular-text" type="text" name="licence-key" />
					</td>
				</tr>
			</table>
				
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Licence'); ?>" />
			</p>

		</form>
		<?php endif ?>

	</div>
	<?php
}
?>