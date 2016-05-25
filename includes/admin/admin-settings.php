<?php

/**select*/

function buddyforms_settings_menu() {

	add_submenu_page( 'edit.php?post_type=buddyforms', __( 'BuddyForms Settings', 'buddyforms' ), __( 'Settings', 'buddyforms' ), 'manage_options', 'bf_settings', 'buddyforms_settings_page' );
}

add_action( 'admin_menu', 'buddyforms_settings_menu' );

function buddyforms_settings_page() {
	global $pagenow, $buddyforms;
	?>
	<div class="wrap">
		<style>
			table.form-table {
				width: 50%;
			}
		</style>
		<?php
		include( BUDDYFORMS_INCLUDES_PATH . '/admin/admin-credits.php' );
		if ( 'true' == esc_attr( $_GET['updated'] ) ) {
			echo '<div class="updated" ><p>BuddyForms...</p></div>';
		}

		if ( isset ( $_GET['tab'] ) ) {
			bf_admin_tabs( $_GET['tab'] );
		} else {
			bf_admin_tabs( 'homepage' );
		}
		?>

		<div id="poststuff">
			<!--			<form method="post" action="-->
			<?php //admin_url( 'edit.php?post_type=buddyforms&page=bf_settings' );
			?><!--">-->
			<?php

			if ( $pagenow == 'edit.php' && $_GET['page'] == 'bf_settings' ) {

				if ( isset ( $_GET['tab'] ) ) {
					$tab = $_GET['tab'];
				} else {
					$tab = 'general';
				}

				switch ( $tab ) {
					case 'general' :
						$buddyforms_posttypes_default = get_option( 'buddyforms_posttypes_default' ); ?>
						<h2><?php _e( 'Post Types Default Form', 'buddyforms' ); ?></h2>
						<p>Select a default form for every post type.</p>

						<p>This will make sure that posts created before BuddyForms will have a form associated. <br>
							If you select none the post edit link will point to the admin for posts not created with
							BuddyForms</p>

						<form method="post" action="options.php">

							<?php settings_fields( 'buddyforms_posttypes_default' ); ?>

							<table class="form-table">
								<tbody>
								<?php
								if ( isset( $buddyforms ) && is_array( $buddyforms ) ) {
									$post_types_forms = Array();
									foreach ( $buddyforms as $key => $buddyform ) {

										if(isset($buddyform['post_type']) && $buddyform['post_type'] != 'bf_submissions' && post_type_exists($buddyform['post_type'])){
											$post_types_forms[ $buddyform['post_type'] ][ $key ] = $buddyform;
										}

									}

									foreach ( $post_types_forms as $post_type => $post_types_form ) : ?>
										<tr valign="top">
											<th scope="row" valign="top">
												<?php
												$post_type_object = get_post_type_object( $post_type );
												echo $post_type_object->labels->name; ?>
											</th>
											<td>
												<select name="buddyforms_posttypes_default[<?php echo $post_type ?>]"
												        class="regular-radio">
													<option value="none">None</option>
													<?php foreach ( $post_types_form as $form_key => $form ) {

														$default = '';
														if(isset($buddyforms_posttypes_default[ $post_type ])){
															$default = $buddyforms_posttypes_default[ $post_type ];
														}
														?>
														<option <?php echo selected( $default, $form_key, true ) ?>
															value="<?php echo $form_key ?>"><?php echo $form['name'] ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
									<?php endforeach;
								} else {
									echo '<h3>You need to create at least one form to select a post type default.</h3>';
								} ?>
								</tbody>
							</table>
							<?php submit_button(); ?>

						</form>
						<?php
						break;
					case 'import-export' :






						$options = get_option( 'pwsix_settings' ); ?>

							<form method="post" action="options.php" class="options_form">
								<?php settings_fields( 'pwsix_settings_group' ); ?>
								<table class="form-table">
									<tr valign="top">
										<th scop="row">
											<label for="pwsix_settings[text]"><?php _e( 'Plugin Text' ); ?></label>
										</th>
										<td>
											<input class="regular-text" type="text" id="pwsix_settings[text]" style="width: 300px;" name="pwsix_settings[text]" value="<?php if( isset( $options['text'] ) ) { echo esc_attr( $options['text'] ); } ?>"/>
											<p class="description"><?php _e( 'Enter some text for the plugin here.'); ?></p>
										</td>
									</tr>
									<tr>
										<th scop="row">
											<label for="pwsix_settings[label]"><?php _e( 'Label Text' ); ?></label>
										</th>
										<td>
											<input class="regular-text" type="text" id="pwsix_settings[label]" style="width: 300px;" name="pwsix_settings[label]" value="<?php if( isset( $options['label'] ) ) { echo esc_attr( $options['label'] ); } ?>"/>
											<p class="description"><?php _e( 'Enter some text for the label here.' ); ?></p>
										</td>
									</tr>
									<tr valign="top">
										<th scop="row">
											<span><?php _e( 'Enable Feature' ); ?></span>
										</th>
										<td>
											<input class="checkbox" type="checkbox" id="pwsix_settings[enabled]" name="pwsix_settings[enabled]" value="1" <?php checked( 1, isset( $options['enabled'] ) ); ?>/>
											<label for="pwsix_settings[enabled]"><?php _e( 'Enable some feature in this plugin?' ); ?></label>
										</td>
									</tr>
								</table>
								<?php submit_button(); ?>
							</form>

							<div class="metabox-holder">
								<div class="postbox">
									<h3><span><?php _e( 'Export Settings' ); ?></span></h3>
									<div class="inside">
										<p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.' ); ?></p>
										<form method="post">
											<p><input type="hidden" name="pwsix_action" value="export_settings" /></p>
											<p>
												<?php wp_nonce_field( 'pwsix_export_nonce', 'pwsix_export_nonce' ); ?>
												<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
											</p>
										</form>
									</div><!-- .inside -->
								</div><!-- .postbox -->

								<div class="postbox">
									<h3><span><?php _e( 'Import Settings' ); ?></span></h3>
									<div class="inside">
										<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></p>
										<form method="post" enctype="multipart/form-data">
											<p>
												<input type="file" name="import_file"/>
											</p>
											<p>
												<input type="hidden" name="pwsix_action" value="import_settings" />
												<?php wp_nonce_field( 'pwsix_import_nonce', 'pwsix_import_nonce' ); ?>
												<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
											</p>
										</form>
									</div><!-- .inside -->
								</div><!-- .postbox -->
							</div><!-- .metabox-holder -->


						<?php






						break;
					case 'recaptcha' :
						$recaptcha = get_option( 'buddyforms_recaptcha' );?>
						<h2><?php _e( 'Google reCaptcha Options' ); ?></h2>
						<form method="post" action="options.php">
							<?php settings_fields("header_section");
							do_settings_sections("recaptcha-options");
							submit_button(); ?>
						</form>
						<?php
						break;
					case 'license' :
						$license = get_option( 'buddyforms_edd_license_key' );
						$status = get_option( 'buddyforms_edd_license_status' ); ?>
						<h2><?php _e( 'Plugin License Options' ); ?></h2>
						<form method="post" action="options.php">

							<?php settings_fields( 'buddyforms_edd_license' ); ?>

							<table class="form-table">
								<tbody>
								<tr valign="top">
									<th scope="row" valign="top">
										<?php _e( 'License Key' ); ?>
									</th>
									<td>
										<input id="buddyforms_edd_license_key" name="buddyforms_edd_license_key"
										       type="text" class="regular-text"
										       value="<?php esc_attr_e( $license ); ?>"/>
										<label class="description"
										       for="buddyforms_edd_license_key"><?php _e( 'Enter your license key' ); ?></label>
									</td>
								</tr>
								<?php if ( false !== $license ) { ?>
									<tr valign="top">
										<th scope="row" valign="top">
											<?php _e( 'Activate License' ); ?>
										</th>
										<td>
											<?php if ( $status !== false && $status == 'valid' ) { ?>
												<span style="color:green;"><?php _e( 'active' ); ?></span>
												<?php wp_nonce_field( 'buddyforms_edd_nonce', 'buddyforms_edd_nonce' ); ?>
												<input type="submit" class="button-secondary"
												       name="edd_license_deactivate"
												       value="<?php _e( 'Deactivate License' ); ?>"/>
											<?php } else {
												wp_nonce_field( 'buddyforms_edd_nonce', 'buddyforms_edd_nonce' ); ?>
												<input type="submit" class="button-secondary"
												       name="edd_license_activate"
												       value="<?php _e( 'Activate License' ); ?>"/>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
							<?php submit_button(); ?>
						</form>
						<?php
						break;
					default:
						do_action( 'buddyforms_settings_page_tab', $tab );
						break;
				}
			}
			?>


		</div>

	</div>

	<?php
}

function buddyforms_register_option() {
	// creates our settings in the options table
	register_setting( 'buddyforms_posttypes_default', 'buddyforms_posttypes_default', 'buddyforms_posttypes_default_sanitize' );
	register_setting( 'buddyforms_edd_license', 'buddyforms_edd_license_key', 'buddyforms_edd_sanitize_license' );
	register_setting( 'pwsix_settings_group', 'pwsix_settings' );
}

add_action( 'admin_init', 'buddyforms_register_option' );

function buddyforms_posttypes_default_sanitize( $new ) {
	return $new;
}

function buddyforms_edd_sanitize_license( $new ) {
	$old = get_option( 'buddyforms_edd_license_key' );
	if ( $old && $old != $new ) {
		delete_option( 'buddyforms_edd_license_status' ); // new license has been entered, so must reactivate
	}

	return $new;
}

/************************************
 * this illustrates how to activate
 * a license key
 *************************************/

function buddyforms_edd_activate_license() {

	// listen for our activate button to be clicked
	if ( isset( $_POST['edd_license_activate'] ) ) {

		// run a quick security check
		if ( ! check_admin_referer( 'buddyforms_edd_nonce', 'buddyforms_edd_nonce' ) ) {
			return;
		} // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'buddyforms_edd_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( BUDDYFORMS_EDD_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( BUDDYFORMS_STORE_URL, array( 'timeout'   => 15,
		                                                         'sslverify' => false,
		                                                         'body'      => $api_params
		) );


		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'buddyforms_edd_license_status', $license_data->license );

	}
}

add_action( 'admin_init', 'buddyforms_edd_activate_license' );


/***********************************************
 * Illustrates how to deactivate a license key.
 * This will descrease the site count
 ***********************************************/

function buddyforms_edd_deactivate_license() {

	// listen for our activate button to be clicked
	if ( isset( $_POST['edd_license_deactivate'] ) ) {

		// run a quick security check
		if ( ! check_admin_referer( 'buddyforms_edd_nonce', 'buddyforms_edd_nonce' ) ) {
			return;
		} // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'buddyforms_edd_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( BUDDYFORMS_EDD_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( BUDDYFORMS_STORE_URL, array( 'timeout'   => 15,
		                                                         'sslverify' => false,
		                                                         'body'      => $api_params
		) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' ) {
			delete_option( 'buddyforms_edd_license_status' );
		}

	}
}

add_action( 'admin_init', 'buddyforms_edd_deactivate_license' );


/************************************
 * this illustrates how to check if
 * a license key is still valid
 * the updater does this for you,
 * so this is only needed if you
 * want to do something custom
 *************************************/

function buddyforms_edd_check_license() {

	global $wp_version;

	$license = trim( get_option( 'buddyforms_edd_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license'    => $license,
		'item_name'  => urlencode( BUDDYFORMS_EDD_ITEM_NAME ),
		'url'        => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( BUDDYFORMS_STORE_URL, array( 'timeout'   => 15,
	                                                         'sslverify' => false,
	                                                         'body'      => $api_params
	) );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( $license_data->license == 'valid' ) {
		echo 'valid';
		exit;
		// this license is still valid
	} else {
		echo 'invalid';
		exit;
		// this license is no longer valid
	}
}

function bf_admin_tabs( $current = 'homepage' ) {
	$tabs = array( 'general' => 'General Settings', 'recaptcha' => 'reCaptcha', 'import-export' => 'Import Export', 'license' => 'License' );

	$tabs = apply_filters( 'bf_admin_tabs', $tabs );

	$links = array();

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='edit.php?post_type=buddyforms&page=bf_settings&tab=$tab'>$name</a>";

	}


	echo '</h2>';
}




















//
// GOOGLE RECHAPTER
//

function display_recaptcha_options() {
	add_settings_section("header_section", "Keys", "display_recaptcha_content", "recaptcha-options");

	add_settings_field("captcha_site_key", __("Site Key"), "display_captcha_site_key_element", "recaptcha-options", "header_section");
	add_settings_field("captcha_secret_key", __("Secret Key"), "display_captcha_secret_key_element", "recaptcha-options", "header_section");

	register_setting("header_section", "captcha_site_key");
	register_setting("header_section", "captcha_secret_key");
}

function display_recaptcha_content() {
	echo __('<p>You need to <a target="_blank" href="https://www.google.com/recaptcha/admin" rel="external">register you domain</a> and get keys to make this plugin work.</p>');
	echo __("Enter the key details below");
}

function display_captcha_site_key_element() { ?>
	<input type="text" name="captcha_site_key" id="captcha_site_key" value="<?php echo get_option('captcha_site_key'); ?>" />
<?php }

function display_captcha_secret_key_element() { ?>
	<input type="text" name="captcha_secret_key" id="captcha_secret_key" value="<?php echo get_option('captcha_secret_key'); ?>" />
<?php }
add_action("admin_init", "display_recaptcha_options");
















//
// Import Export
//

/**
 * Process a settings export that generates a .json file of the shop settings
 */
function pwsix_process_settings_export() {
	if( empty( $_POST['pwsix_action'] ) || 'export_settings' != $_POST['pwsix_action'] )
		return;
	if( ! wp_verify_nonce( $_POST['pwsix_export_nonce'], 'pwsix_export_nonce' ) )
		return;
	if( ! current_user_can( 'manage_options' ) )
		return;
	$settings = get_option( 'pwsix_settings' );
	ignore_user_abort( true );
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=pwsix-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );
	echo json_encode( $settings );
	exit;
}
add_action( 'admin_init', 'pwsix_process_settings_export' );
/**
 * Process a settings import from a json file
 */
function pwsix_process_settings_import() {
	if( empty( $_POST['pwsix_action'] ) || 'import_settings' != $_POST['pwsix_action'] )
		return;
	if( ! wp_verify_nonce( $_POST['pwsix_import_nonce'], 'pwsix_import_nonce' ) )
		return;
	if( ! current_user_can( 'manage_options' ) )
		return;
	$extension = end( explode( '.', $_FILES['import_file']['name'] ) );
	if( $extension != 'json' ) {
		wp_die( __( 'Please upload a valid .json file' ) );
	}
	$import_file = $_FILES['import_file']['tmp_name'];
	if( empty( $import_file ) ) {
		wp_die( __( 'Please upload a file to import' ) );
	}
	// Retrieve the settings from the file and convert the json object to an array.
	$settings = (array) json_decode( file_get_contents( $import_file ) );
	update_option( 'pwsix_settings', $settings );
	wp_safe_redirect( admin_url( 'edit.php?post_type=buddyforms&page=bf_settings&tab=import-export' ) ); exit;
}
add_action( 'admin_init', 'pwsix_process_settings_import' );
?>
