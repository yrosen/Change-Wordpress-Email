<?php 
/*
Plugin Name: Change WordPress Email
Version: 1.0
Plugin URI: http://www.yudirosen.com
Description: This plugin allows you to set the From header on emails sent by WP - mail-from plugin on the wp.org site doesn't work with new WP
Author: Yudi Rosen <yudi42@gmail.com>
*/

// The name & email that we've already got set:
$preset_email = get_option('site_mail_from_email');
$preset_name  = get_option('site_mail_from_name');


// Add filters for wp_mail() - wp_mail_from:
add_filter('wp_mail_from', 
	function ($email) use ($preset_email) {
		// If site_mail_from_email is already set, use that, else use new setting:
		return empty($preset_email) ? $email : $preset_email;
	},
1);


// Add filters for wp_mail() - wp_mail_from_name:
add_filter('wp_mail_from_name',
	function ($name) use ($preset_name) {
		// If site_mail_from_email_name is already set, use that, else use new setting:
		return empty($preset_name) ? $name : $preset_name;
	},
1);


// Add link to admin menu:
add_action('admin_menu',
	function () use($preset_email, $preset_name) {
		add_options_page('Change WordPress Email', 'Change WP Email', 'administrator', __FILE__, 

			// The Settings page:
			function () use($preset_email, $preset_name) {
				$wp_nonce_field = wp_nonce_field('update-options', '_wpnonce', true, false);
	
				echo <<<HTML
				<div class="wrap">
					<h2>Change WordPress Email</h2>
					<i>If set, these 2 options will override the email address and name in the <strong>From:</strong> header on all emails sent from WordPress.</i>

					<form method="post" action="options.php">
						
						<table class="form-table">
							<tr><th scope="row">From email address</th>
							<td><input type="text" name="site_mail_from_email" value="{$preset_email}" /></td></tr>

							<tr><th scope="row">From email name</th>
							<td><input type="text" name="site_mail_from_name" value="{$preset_name}" /></td></tr>
						</table>

						{$wp_nonce_field}
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="page_options" value="site_mail_from_email,site_mail_from_name" />

						<p class="submit">
							<input type="submit" name="Submit" value="Submit" />
						</p>
					</form>
			  </div>
HTML;
			}
			// END Settings page
		);
	}
);
?>