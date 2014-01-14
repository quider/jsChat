<?php
/*
Plugin Name: ConverseJS
Plugin URI: https://conversejs.org/
Description: This plugin add the javascript code for Converse.js a Jabber/XMPP chat for your WordPress.
Version: 1.0.2
Author: camaran
Author URI: http://www.chatme.im
*/

/*  Copyright 2012  Thomas Camaran  (email : camaran@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('wp_head', 'get_converse_head');
add_action('wp_footer', 'get_converse_footer');
add_action('admin_menu', 'converse_menu');
add_action('admin_init', 'register_converse_mysettings' );

add_action( 'init', 'my_plugin_init' );

function my_plugin_init() {
      $plugin_dir = basename(dirname(__FILE__));
      load_plugin_textdomain( 'conversejs-lng', null, $plugin_dir . '/languages/' );
}

function get_converse_head() {
	if(get_option('conversion_url') == '')
		$url = "https://converse.chatme.im";
	else
		$url = get_option('conversion_url');
	
	echo "\n".'<link rel="stylesheet" type="text/css" href="'.$url.'/converse.css">';
	echo "\n".'<script type="text/javascript" src="'.$url.'/builds/converse.min.js"></script>';
}

function get_converse_footer() {

	if(get_option('language') == '')
		$lng = "en";
	else
		$lng = get_option('language');
	if(get_option('bosh') == '')
		$bsh = "https://api.chatme.im/http-bind/";
	else
		$bsh = get_option('bosh');		

echo "\n".'<!-- Messenger -->
	<script>
		require([\'converse\'], function (converse) {
		    converse.initialize({
		        auto_list_rooms: false,
		        auto_subscribe: false,
		        bosh_service_url: \''.$bsh.'\',
		        hide_muc_server: false,
		        i18n: locales.'.$lng.',
		        prebind: false,
		        show_controlbox_by_default: true,
		        xhr_user_search: false
		    });
		});
	</script>
	<div id="conversejs"></div>';
}

function converse_menu() {
  add_options_page('ConverseJS', 'ConverseJS', 'manage_options', 'converse-identifier', 'converse_options');
}

function register_converse_mysettings() {
	//register our settings
	register_setting('converse_options_list', 'language');
	register_setting('converse_options_list', 'conversion_url');
	register_setting('converse_options_list', 'bosh');
}

function converse_options() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.', 'conversejs-lng') );
  }
 ?>
 <div class="wrap">
<h2>ConverseJS</h2>
<p><?php _e("For more information visit <a href='http://www.chatme.im' target='_blank'>www.chatme.im</a>", 'conversejs-lng'); ?> - <a href="https://webchat.chatme.im/?r=support" target="_blank">Support Chat Room</a> - <a href="https://conversejs.org/" trget="_blank">ConverseJS.org</a></p> 

<form method="post" action="options.php">
    <?php settings_fields( 'converse_options_list' ); ?>
    <table class="form-table">

        <tr valign="top">
        <th scope="row"><?php _e("Converse Installation URL", 'conversejs-lng'); ?></th>
        <td>
        <input id="conversion_url" name="conversion_url" type="url" placeholder="<?php _e("Conversion JS URL", 'conversejs-lng'); ?>" value="<?php echo get_option('conversion_url'); ?>">/<br/><em><?php _e("We suggest https://converse.chatme.im", 'conversejs-lng'); ?></em>
        </td>
        </tr>  
    
        <tr valign="top">
        <th scope="row"><?php _e("Bosh Server", 'conversejs-lng'); ?></th>
        <td>
        <input id="bosh" name="bosh" type="url" placeholder="<?php _e("bosh service", 'conversejs-lng'); ?>" value="<?php echo get_option('bosh'); ?>"><br/><em><?php _e("We suggest http://api.chatme.im/http-bind/", 'conversejs-lng'); ?></em>
        </td>
        </tr>    
    
        <tr valign="top">
        <th scope="row"><?php _e("Converse language", 'conversejs-lng'); ?></th>
        <td>
        <select id="language" name="language">
        <option value="de" <?php selected('de', get_option('language')); ?>>Deutsch</option>
        <option value="en" <?php selected('en', get_option('language')); ?>>English</option>
        <option value="es" <?php selected('es', get_option('language')); ?>>Español</option>
        <option value="fr" <?php selected('fr', get_option('language')); ?>>Français</option>
        <option value="it" <?php selected('it', get_option('language')); ?>>Italiano</option>
        <option value="ja" <?php selected('ja', get_option('language')); ?>>日本語</option>
        <option value="nl" <?php selected('nl', get_option('language')); ?>>Nederlands</option>
        <option value="ru" <?php selected('ru', get_option('language')); ?>>Русский</option>
        </select>
        </td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'conversejs-lng') ?>" />
    </p>
    <p>For Ever request you can use our <a href="http://chatme.im/forums" target="_blank">forum</a></p>

</form>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8CTUY8YDK5SEL">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>

<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://chatme.im" data-text="Visita chatme.im" data-via="chatmeim" data-lang="it">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>
<?php } ?>