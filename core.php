<?php
/*
Plugin Name: Custom Code Adder by AceApp Studios
Plugin URI: https://www.aceappstudios.com/shop/free/custom-code-adder-free-plugin-for-wordpress/
Description: Custom Code Adder by AceApp Studios. Add any code to your website without editing the core files. Easily add HTML, PHP, JavaScript, or almost any other code to your Wordpress site. Codes are stored safely in the database so that when switching themes or updating the plugin your custom code is not lost.
Version: 1.0
Author: AceApp Studios
Author URI: https://www.aceappstudios.com/shop/
*/

// 20/08/2014 - Last modification to plugin.
 
// Init plugin options to white list our options
function aceapp_cca_init(){
	register_setting( 'aceapp_cca_plugin_options', 'aceapp_cca_options', 'aceapp_cca_validate_options' );
}
add_action('admin_init', 'aceapp_cca_init' );
 
 #### Global Values
if (!defined('ACEAPPCCA_THEME_DIR'))
    define('ACEAPPCCA_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('ACEAPPCCA_PLUGIN_NAME'))
    define('ACEAPPCCA_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('ACEAPPCCA_PLUGIN_DIR'))
    define('ACEAPPCCA_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . ACEAPPCCA_PLUGIN_NAME);

if (!defined('ACEAPPCCA_PLUGIN_URL'))
    define('ACEAPPCCA_PLUGIN_URL', WP_PLUGIN_URL . '/' . ACEAPPCCA_PLUGIN_NAME);
	
#### Custom Globals
$logo = ACEAPPCCA_PLUGIN_URL . '/images/logo.png';
 
##### Add plugin admin page
add_action('admin_menu', 'ccaplugin_menu_pages');

function ccaplugin_menu_pages() {
    // Add the top-level admin menu
    $page_title = 'Custom Code Adder Plugin Settings';
    $menu_title = 'Custom Code Adder';
    $capability = 'manage_options';
    $menu_slug = 'ccaplugin-settings';
    $function = 'ccaplugin_settings';
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);

    // Add submenu page with same slug as parent to ensure no duplicates
    $sub_menu_title = 'Code Settings';
    add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

    // Now add the submenu page for Help
    $submenu_page_title = 'Custom Code Adder Plugin Help';
    $submenu_title = 'Help';
    $submenu_slug = 'ccaplugin-help';
    $submenu_function = 'ccaplugin_help';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
	
    // Now add the submenu page for Premim Plugin
    $submenu_page_title = 'Custom Code Adder Donations';
    $submenu_title = 'Donations';
    $submenu_slug = 'ccaplugin-premium';
    $submenu_function = 'ccaplugin_premium';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
}

#adding settings link
add_filter('plugin_action_links', 'aceapp_cca_plugin_action_links', 10, 2);

function aceapp_cca_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=ccaplugin-settings">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

#Settings page
function ccaplugin_settings() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }



// Render the Plugin options form

#function aceapp_cca_render_form() {
	?>
	<div class="wrap" width="50%">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Custom Code Adder by AceApp Studios</h2>
		<p><?php settings_fields('aceapp_cca_plugin_options'); ?>
			<?php $options = get_option('aceapp_cca_options'); ?>
			<?php 
			if ($options['textarea_five'] == 'OFF') {
			print "<h2>Checkout our <a href=\"https://www.aceappstudios.com/shop/all/wp-plugin-creator-for-wordpress-create-powerful-plugins-for-wordpress/\" taerget=\"_blank\">Wordpress Plugin Creator</a> by AceApp Studios.<br />Simply create plugins with a click!</h2>";
			print "Powered link is <font color=\"darkred\"><b>disabled</b></font>. Please concider making a small donation to our plugin. <br />";
			$aceapp_donations = <<<ACEAPPDONATIONS
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YR5GPJAVGBARN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
ACEAPPDONATIONS;
			echo $aceapp_donations;
			}
			

			else {
			
			}
			?></p>
		<p style="float: right"><a href="https://www.aceappstudios.com" target="_blank"><img src="<?php 
		$logo = ACEAPPCCA_PLUGIN_URL . '/images/logo.png';
		print $logo;
		?>"></a></p>
		<form method="post" action="options.php">
			<?php settings_fields('aceapp_cca_plugin_options'); ?>
			<?php $options = get_option('aceapp_cca_options'); ?>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save All Custom Codes') ?>" />
			</p>
			<table class="form-table">
				<tr>
					<td>
						<h2>Above post &amp; page content</h2>
						<?php
							$args = array("textarea_name" => "aceapp_cca_options[textarea_one]");
							wp_editor( $options['textarea_one'], "aceapp_cca_options[textarea_one]", $args );
						?>
					</td>
					<td>(Using the_content filter/echo)
					<p>Useful for advert promotions or any extra code you would like to show above your content posts and pages.</p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Below post &amp; page content</h2>
						<?php
							$args = array("textarea_name" => "aceapp_cca_options[textarea_two]");
							wp_editor( $options['textarea_two'], "aceapp_cca_options[textarea_two]", $args );
						?>
					</td>
					<td>(Using the_content filter/return)
					<p>Useful for advert promotions or any extra code you would like to show below your content posts and pages.</p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Footer</h2>
						<?php
							$args = array("textarea_name" => "aceapp_cca_options[textarea_four]");
							wp_editor( $options['textarea_four'], "aceapp_cca_options[textarea_four]", $args );
						?>
					</td>
					<td> (Using wp_footer action/echo)
					<p>Useful for advert promotions or any extra code you would like to show in the footer of your website.</p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Head Code</h2>
						<p><font color="darkred"><b>WARNING:</b></font> <em>Use with caution incorrect code entererd in here may stop your site from responding correctly.</em></p>
						<textarea id="aceapp_cca_options[textarea_three]" name="aceapp_cca_options[textarea_three]" rows="7" cols="150" type='textarea'><?php echo $options['textarea_three']; ?></textarea>
					</td>
					<td> (Using wp_head action)
					<p>Used for insering code and scripts into the head of your website.<br />
					Recomended for experianced coders...<br /></p>
					<p><font color="darkred"><b>WARNING:</b></font> <em>Use with caution incorrect code entererd in here may stop your site from responding correctly.</em></p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Powered link</h2>
						<p><font color="darkred"><b>To remove 'Powered' link:</b></font> <em>Just type OFF in the box.</em></p>
						<textarea id="aceapp_cca_options[textarea_five]" name="aceapp_cca_options[textarea_five]" rows="1" cols="5" type='textarea'><?php echo $options['textarea_five']; ?></textarea>
					</td>
					<td>&nbsp;
					<td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save All Custom Codes') ?>" />
			</p>
		</form>
	</div>
			<?php settings_fields('aceapp_cca_plugin_options'); ?>
			<?php $options = get_option('aceapp_cca_options'); ?>
			<?php 
			if ($options['textarea_five'] == 'OFF') {
			print "<h2>Checkout our <a href=\"https://www.aceappstudios.com/shop/all/wp-plugin-creator-for-wordpress-create-powerful-plugins-for-wordpress/\" taerget=\"_blank\">Wordpress Plugin Creator</a> by AceApp Studios.<br />Simply create plugins with a click!</h2>";
			print "Powered link is <font color=\"darkred\"><b>disabled</b></font>. Please concider making a small donation to our plugin. <br />";
			$aceapp_donations = <<<ACEAPPDONATIONS
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YR5GPJAVGBARN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
ACEAPPDONATIONS;
			echo $aceapp_donations;
			}
			

			else {
			print "<h2><font color=\"darkgreen\">You ROCK!</font> Thank you for displaying our 'Powered' link</h2>";
			}
			?>
	<p style="text-align: center"><a href="https://www.aceappstudios.com/shop/" target="_blank"><img src="<?php 
		$logo = ACEAPPCCA_PLUGIN_URL . '/images/logo.png';
		print $logo;
		?>"></a></p>
	<?php	
}

function ccaplugin_help() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Render the HTML for the Help page or include a file that does
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Custom Code Adder Help</h2>
		<h3>Our contact details for our help &amp; support!</h3>
		<p>Help and Support: pluginhelp@aceappstudios.com<br />
		If you think you have found a bug: bugs@aceappstudios.com<br />
		Suggestions for updates, new plugins or software: suggest@aceappstudios.com</p>
		<p>Please also include your version number (FREE v1.5) that you are using.</p>
		<p>We will usually answer emails within 24 hours (please be paitent as we dont have many staff).</p>
		<p style="text-align: center"><a href="https://www.aceappstudios.com/shop/" target="_blank"><img src="<?php 
		$logo = ACEAPPCCA_PLUGIN_URL . '/images/logo.png';
		print $logo;
		?>"></a></p>
		
	</div>
	<?php
}

function ccaplugin_premium() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Render the HTML for the Premium Plugin page or include a file that does
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Custom Code Adder Donations</h2>
		<p>Please concider donation to the progress of this plugin...</p>
		<?php
		$aceapp_donations = <<<ACEAPPDONATIONS
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YR5GPJAVGBARN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
ACEAPPDONATIONS;
			echo $aceapp_donations;
		?>
		<?php settings_fields('aceapp_cca_plugin_options'); ?>
			<?php $options = get_option('aceapp_cca_options'); ?>
			<?php 
			if ($options['textarea_five'] == 'OFF') {
			print "<h2>Checkout our <a href=\"https://www.aceappstudios.com/shop/all/wp-plugin-creator-for-wordpress-create-powerful-plugins-for-wordpress/\" taerget=\"_blank\">Wordpress Plugin Creator</a> by AceApp Studios.<br />Simply create plugins with a click!</h2>";
			print "Powered link is <font color=\"darkred\"><b>disabled</b></font>. Please concider making a small donation to our plugin. <br />";
			}
			
			else {
			print "<h2><font color=\"darkgreen\">You ROCK!</font> Thank you for displaying our 'Powered' link</h2>";
			}
			?>
	</div>
	<?php
}

#}
 
// Sanitize and validate input. Accepts an array, return a sanitized array.
function aceapp_cca_validate_options($input) {
	// Sanitize textarea input (strip html tags, and escape characters)
	//$input['textarea_one'] = wp_filter_nohtml_kses($input['textarea_one']);
	//$input['textarea_two'] = wp_filter_nohtml_kses($input['textarea_two']);
	//$input['textarea_three'] = wp_filter_nohtml_kses($input['textarea_three']);
	return $input;
}

// Functions
# head
function aceapp_cca_head_func($options) {
$options = get_option('aceapp_cca_options');
echo ''.$options['textarea_three'].'';
}

#content
function aceapp_cca_content_func($contentcode1) {
$options = get_option('aceapp_cca_options');
echo ''.$options['textarea_one'].'';

	if (!is_page()&&!is_feed()) {
		$options = get_option('aceapp_cca_options');
		$contentcode1 .= ''.$options['textarea_two'].'';
		return $contentcode1;

	}
	else {
		$options = get_option('aceapp_cca_options');
		$contentcode1 .= ''.$options['textarea_two'].'';
		return $contentcode1;
	}
	
}

#content home
function aceapp_cca_home_content_func($contentcode1) {
$options = get_option('aceapp_cca_options');
echo ''.$options['textarea_one'].'';

	if (!is_page()&&!is_feed()) {
		$options = get_option('aceapp_cca_options');
		$contentcode1 .= '';
		return $contentcode1;

	}
	else {
		$options = get_option('aceapp_cca_options');
		$contentcode1 .= '';
		return $contentcode1;
	}
	
}

#footer
function aceapp_cca_footer_func($options) {
$options = get_option('aceapp_cca_options');
echo ''.$options['textarea_four'].'';
}

#footer2
function aceapp_cca_footer_func2($options) {
$options = get_option('aceapp_cca_options');
if ($options['textarea_five'] == 'OFF') {
}
else {
echo '<br /><div style="float:left"><span style="color: #c0c0c0;">Powered with</span>
<a title="Custom Code Adder by AceApp Studios" href="https://www.aceappstudios.com/shop/free/custom-code-adder-free-plugin-for-wordpress/" target="_blank" style="text-decoration=none">Custom Code Adder</a></div>'; // *** Do not remove our 'Powered' code *** >>> You can disable from setting page !!!
}
}

function aceapp_cca_query($query) {
	if ( !is_front_page() ) {
		return add_filter('the_content', 'aceapp_cca_content_func', 1);
    }
	else {
		return;
	}

}

// Action Hooks
add_action('wp_head', 'aceapp_cca_head_func', 1);
add_action('wp_footer', 'aceapp_cca_footer_func', 1);
add_filter( 'pre_get_posts', 'aceapp_cca_query' );

add_action('wp_footer', 'aceapp_cca_footer_func2', 2);


?>