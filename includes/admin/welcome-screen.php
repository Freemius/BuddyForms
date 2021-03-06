<?php

//
// Add the Settings Page to the BuddyForms Menu
//
function buddyforms_welcome_screen_menu() {

	add_submenu_page( 'edit.php?post_type=buddyforms', __( 'Info', 'buddyforms' ), __( 'Info', 'buddyforms' ), 'manage_options', 'buddyforms_welcome_screen', 'buddyforms_welcome_screen_content' );

}

add_action( 'admin_menu', 'buddyforms_welcome_screen_menu', 9999 );

add_action( 'admin_init', 'buddyforms_welcome_screen_do_activation_redirect' );
function buddyforms_welcome_screen_do_activation_redirect() {
	// Bail if no activation redirect
	if ( ! get_transient( '_buddyforms_welcome_screen_activation_redirect' ) ) {
		return;
	}

	// Delete the redirect transient
	delete_transient( '_buddyforms_welcome_screen_activation_redirect' );

	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}

	// Redirect to BuddyForms about page
//	wp_safe_redirect( add_query_arg( array( 'post_type' => 'buddyforms', 'page' => 'buddyforms_welcome_screen' ), admin_url('edit.php') ) );
//
}

function buddyforms_welcome_screen_content() {
	?>
	<div id="bf_admin_wrap" class="wrap">

		<?php // include( 'bf-admin-header.php' ); ?>

		<style>
			/* Welcome Page CSS */

			.about-wrap.buddyforms-welcome {
				margin-top: 40px;
			}

			.about-wrap.buddyforms-welcome .lead {
				max-width: none;
				margin: 20px 0;
			}

			.about-wrap.buddyforms-welcome .feature-section h1 {
				max-width: none;
				margin: 40px 0 20px;
				font-weight: 300;
			}

			.about-wrap.buddyforms-welcome h2 {
				max-width: none;
				margin: 40px 0 20px;
				text-align: left;
			}

			.about-wrap.buddyforms-welcome .about-text {
				min-height: 40px;
				margin-top: 20px;
				font-size: 23px;
				color: #32373c;
				margin-bottom: 30px;
				font-weight: 300;
			}

			.bfw-section {
				margin: 70px 0;
				overflow: auto;
			}

			.bfw-row {
				overflow: auto;
				clear: both;
			}

			.bfwell {
				margin: 40px 0 0 0;
				background: #e5e5e5;
				overflow: auto;
				border: 1px solid #ccc;
				padding: 20px 10px;
			}

			.bfw-col {
				display: block;
				float: left;
				width: 100%;
				overflow: auto;
				padding: 10px;
				box-sizing: border-box;
			}

			.bfw-col-40 {
				width: 40%;
			}

			.bfw-col-50 {
				width: 50%;
			}

			.bfw-col-60 {
				width: 60%;
			}

			.bfw-well {
				padding: 20px;
				background: #fafafa;
				border: 1px solid rgba(0, 0, 0, 0.1);
			}

			.about-wrap.buddyforms-welcome .bfw-title {
				margin-top: 0;
				font-weight: 300;
			}
		</style>


		<div class="wrap about-wrap buddyforms-welcome">

			<h1>Welcome to BuddyForms&nbsp;2.0</h1>

			<p class="about-text">Enjoy Groundbreaking New Features!</p>

			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="about.php" class="nav-tab nav-tab-active">What’s New</a>
				<a href="https://themekraft.com/buddyforms/#extensions" target="_new" title="Browse BuddyForms Add-ons"
				   class="nav-tab">BuddyForms Add-ons</a>
			</h2>


			<div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

				<div class="xcol col-big">
					<h2>A Revolutionary Form Wizard</h2>
					<p class="lead">
						<b>Never feel lost again.</b> Setting up your custom forms will be a breeze.
					</p>
				</div>

				<div class="xcol col-small">
					<div class="imgframe">
						<img class="nopad"
						     style="margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd;"
						     src="https://1l1jrk1lr1oc1721v72mdabi-wpengine.netdna-ssl.com/wp-content/themes/themekraft-2017/includes/img/buddyforms-form-wizard-screenshot-small.jpg"
						     alt="BuddyForms Form Wizard Screenshot">
					</div>
				</div>

			</div>

			<hr>


			<div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

				<div class="xcol col-big">
					<h2>All Form Types</h2>
					<p class="lead">
						Contact Forms. Signup Forms. Post Forms.
					</p>
				</div>

				<div class="xcol col-small">
					<div class="imgframe">
						<img class="nopad"
						     style="width: 800px; height: auto; max-width: 100%; margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd;"
						     src="https://1l1jrk1lr1oc1721v72mdabi-wpengine.netdna-ssl.com/wp-content/themes/themekraft-2017/includes/img/buddyforms-formbuilder-screenshot-01.jpg"
						     alt="BuddyForms Form Wizard Screenshot">
					</div>
				</div>

			</div>

			<hr>

			<div class="feature-section two-col" style="margin: 30px 0; overflow: auto;">

				<div class="xcol col-big">
					<h2>Admin Metabox Support</h2>
					<p class="lead">
						Speed up your workflow for post moderation.
					</p>
					<p class="lead">
						Edit user submitted posts quickly within the backend and find all special form fields in an
						extra metabox - easy to edit!
					</p>
				</div>

				<div class="xcol col-small">
					<div class="imgframe">
						<img class="nopad"
						     style="width: 800px; height: auto; max-width: 100%; margin: 10px 0; padding: 5px; background: #fff; border: 1px solid #ddd;"
						     src="https://1l1jrk1lr1oc1721v72mdabi-wpengine.netdna-ssl.com/wp-content/themes/themekraft-2017/includes/img/buddyforms-formbuilder-screenshot-01.jpg"
						     alt="BuddyForms Form Wizard Screenshot">
					</div>
				</div>

			</div>


			<!-- Blogpost & Changelog -->
			<div class="bfw-section bfw-news" style="margin-top: 30px;">
				<div class="bfw-col bfw-col-50">
					<h2 class="bfw-title">Latest Blogpost</h2>
					<p class="lead">Read all about this new BuddyForms version:</p>
					<a href="#" target="_new" class="button button-primary">Read Blogpost</a>
				</div>
				<div class="bfw-col bfw-col-50">
					<h2 class="bfw-title">Changelog</h2>
					<p class="lead">Check out the changelog for version 2.0</p>
					<a href="#" target="_new" class="button button-primary">View Changelog</a></p>
				</div>
			</div>


			<hr style="margin: 70px 0;">


			<!-- Getting Started -->
			<div class="bfw-section bfw-getting-started">
				<div class="bfw-col bfw-col-50">
					<div class="well">
						<h2 class="bfw-title">First Time Here?</h3>
							<a class="button xbutton-primary" href="#" title="" target="new">Getting Started</a>
					</div>
				</div>
				<div class="bfw-col bfw-col-50">
					<div class="well">
						<h2 class="bfw-title">How To Create New Forms</h3>
							<a class="button xbutton-primary" href="#" title="" target="new">Contact Form</a>
							<a class="button xbutton-primary" href="#" title="" target="new">Registration Form</a>
							<a class="button xbutton-primary" href="#" title="" target="new">Post Form</a>
					</div>
				</div>
			</div>


			<!-- Docs, Support & Custom Work -->
			<div class="bfw-section bfw-docs" style="margin-bottom: 100px;">
				<!-- <div class="bfw-col bfw-col-50">
					<h2 class="bfw-title">Support &amp; Docs</h2>
					<p class="lead">Get quick help.</p>
					<a class="button xbutton-primary" href="http://docs.buddyforms.com" title="" target="new">Documentation</a>
					<a class="button xbutton-primary" href="https://themekraft.com/support" title="" target="new">Support</a>
				</div> -->
				<div class="bfw-col bfw-col-50">
					<h1 class="bfw-title">Custom Work</h1>
					<p class="lead">Need a custom setup?</p>
					<a class="button button-primary" href="mailto:support@buddyforms.com" title=""
					   target="new">Enquire</a>
				</div>
			</div>


		</div>


	</div>
	<?php
}
