<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
Plugin Name: ConverseJS
Plugin URI: https://conversejs.org/
Description: This plugin add the javascript code for Converse.js a Jabber/XMPP chat for your WordPress.
Version: 2.3.2
Author: camaran
Author URI: http://www.chatme.im
Text Domain: conversejs
Domain Path: /languages/
*/

class converseJS {
	
private $default 	= array(
						'languages' 			=> '/languages/',
						'language' 			=> 'en',	
						'webchat' 			=> 'https://bind.chatme.im/',
						'providers_link'		=> 'http://chatme.im/servizi/domini-disponibili/',
						'placeholder'			=> ' e.g. chatme.im',
						'call'				=> 'false',
						'carbons'			=> 'false',
						'foward'			=> 'false',
						'panel'				=> 'true',
						'conver'			=> '0.9.4',
						'custom'			=> '',
						'clear'				=> 'false', 
						'emoticons'			=> 'false', 
						'toggle_participants'		=> 'false', 
						'play_sounds'			=> 'false',
						'xhr_user_search'		=> 'false',
						'prebind'			=> 'false',
						'hide_muc_server'		=> 'false',
						'auto_list_rooms'		=> 'false',
		        			'auto_subscribe'		=> 'false',
						'bosh_type'			=> 'bosh_service_url',
						'sounds_path'			=> './sounds',
						);

	function __construct() {
		add_action('wp_enqueue_scripts', 	array( $this, 'get_converse_head') );
		add_action('wp_footer', 		array( $this, 'get_converse_footer') );
		add_action('admin_menu', 		array( $this, 'converse_menu') );
		add_action('admin_init', 		array( $this, 'register_converse_mysettings') );
		add_action( 'init', 			array( $this, 'my_plugin_init') );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_action_converse_links') );
		}

	function my_plugin_init() {
      	$plugin_dir = basename(dirname(__FILE__));
      	load_plugin_textdomain( 'conversejs', null, $plugin_dir . $this->default['languages'] );
		}

      	function add_action_converse_links ( $links ) {
      	$mylinks = array( '<a href="' . admin_url( 'admin.php?page=converse-identifier' ) . '">' . __( 'Settings', 'conversejs' ) . '</a>', );
      	return array_merge( $links, $mylinks );
      	      	}

      	function converse_add_help_tab () {
          	$screen = get_current_screen();

          	$screen->add_help_tab( array(
              	      	'id'		=> 'converse_help_tab',
              	      	'title'		=> __('Bosh Server', 'conversejs'),
              	      	'content'	=> '<p>' . __( 'The Bind Server used from ConverseJS to connect to XMPP server, you can use <b>https://bind.chatme.im</b> for all XMPP service login.<br/><br/>Variable: <i>bosh_service_url</i><br/>Default value: <i>https://bind.chatme.im</i>', 'conversejs' ) . '</p>',
          	      	) );

          	$screen->add_help_tab( array(
              	      	'id'		=> 'converse_help_tab_2',
              	      	'title'		=> __('WebSocket Server', 'conversejs'),
              	      	'content'	=> '<p>' . __( 'The WebSocket Server used from ConverseJS to connect to XMPP server, you can use <b>wss//ws.chatme.im</b> for all XMPP service login.<br/><br/>Variable: <i>websocket_url</i><br/>Default value: <i>wss://ws.chatme.im</i><br /><br />WebSocket server is more fast than bosh server but work only with modern browsers', 'conversejs' ) . '</p>',
          	      	) );

          	$screen->add_help_tab( array(
              	      	'id'		=> 'converse_help_tab_3',
              	      	'title'		=> __('Provider Link', 'conversejs'),
              	      	'content'	=> '<p>' . __( 'The link with XMPP service list, for example <b>http://chatme.im/servizi/domini-disponibili/</b>.<br/><br/>Variable: <i>providers_link</i><br/>Default value: <i>http://chatme.im/servizi/domini-disponibili/</i>', 'conversejs' ) . '</p>',
          	      	) );

          	$screen->add_help_tab( array(
              	      	'id'		=> 'converse_help_tab_4',
              	      	'title'		=> __('Register Placeholder', 'conversejs'),
              	      	'content'	=> '<p>' . __( 'The placeholder that show in register page.<br/><br/>Variable: <i>domain_placeholder</i><br/>Default value: <i>e.g. chatme.im</i>', 'conversejs' ) . '</p>',
          	      	) );

          	$screen->set_help_sidebar(
                              __('<p><strong>Other Resources</strong></p><p><a href="https://conversejs.org/" target="_blank">ConverseJS Official Site</a></p><p><a href="https://conversejs.org/docs/html/index.htmls" target="_blank">ConverseJS Official Documentation</a></p><p><a href="http://xmpp.net" target="_blank">XMPP.net</a></p><p><a href="http://chatme.im" target="_blank">ChatMe Site</a></p>', 'conversejs')
                             );
      	      	}

	function get_converse_head() {
	
		wp_register_style( 'ConverseJS', plugins_url( '/core/css/converse.min.css', __FILE__ ), array(), $this->default['conver'] );
		wp_enqueue_style( 'ConverseJS' );
		wp_register_script( 'ConverseJS', plugins_url( '/core/converse.min.js', __FILE__ ), array(), $this->default['conver'], false );
		wp_enqueue_script( 'ConverseJS' );
		}

	function get_converse_footer() {
		
		$setting	= array(
						'language' 				=> esc_html(get_option('language')),	
						'webchat' 				=> esc_url(get_option('bosh')),
						'providers_link'		=> esc_url(get_option('providers_link')),
						'placeholder'			=> esc_html(get_option('placeholder')),
						'call'					=> esc_html(get_option('call')),
						'carbons'				=> esc_html(get_option('carbons')),
						'foward'				=> esc_html(get_option('foward')),
						'panel'					=> esc_html(get_option('panel')),	
						'custom'				=> esc_js(get_option('custom')),	
						'clear'					=> esc_html(get_option('clear')), 
						'emoticons'				=> esc_html(get_option('emoticons')), 
						'toggle_participants'	=> esc_html(get_option('toggle_participants')), 
						'play_sounds'			=> esc_html(get_option('play_sounds')),
						'xhr_user_search'		=> esc_html(get_option('xhr_user_search')),
						'prebind'				=> esc_html(get_option('prebind')),
						'hide_muc_server'		=> esc_html(get_option('hide_muc_server')),
						'auto_list_rooms'		=> esc_html(get_option('auto_list_rooms')),
		        			'auto_subscribe'		=> esc_html(get_option('auto_subscribe')),	
						'bosh_type'			=> esc_html(get_option('bosh_type')),
						'sounds_path'			=> esc_html(get_option('sounds_path')),
		
						);
						
		foreach( $setting as $k => $settings )
			if( false == $settings )
				unset( $setting[$k]);
						
		$actual = wp_parse_args( $setting, $this->default );							
		
		printf( '
		
		<!-- Messenger -->
		<script>
			require([\'converse\'], function (converse) {
		    	converse.initialize({
		        	auto_list_rooms: %s,
		        	auto_subscribe: %s,
		        	%s: \'%s\',
		        	hide_muc_server: %s,
		        	i18n: locales.%s,
		        	prebind: %s,
		        	show_controlbox_by_default: %s,
		        	xhr_user_search: %s,		        		           
              			message_carbons: %s,
               			forward_messages: %s,
				domain_placeholder: "%s",
				providers_link: "%s",
				play_sounds: %s,
				sounds_path: \'%s\',
				%s
				visible_toolbar_buttons: { call: %s, clear: %s, emoticons: %s, toggle_participants: %s}
		    	});
			});
		</script>',
				$actual['auto_list_rooms'],
		        	$actual['auto_subscribe'],
		        	$actual['bosh_type'],
				$actual['webchat'],
				$actual['hide_muc_server'],
				$actual['language'],
				$actual['prebind'],
				$actual['panel'],
				$actual['xhr_user_search'],
				$actual['carbons'],
				$actual['foward'],
				$actual['placeholder'],
				$actual['providers_link'],
				$actual['play_sounds'],
				$actual['sounds_path'],
				$actual['custom'],
				$actual['call'],
				$actual['clear'], 
				$actual['emoticons'], 
				$actual['toggle_participants']
				);
	}

	function converse_menu() {
  		$my_admin_page = add_options_page('ConverseJS', 'ConverseJS', 'manage_options', 'converse-identifier', array($this, 'converse_options') );
  		add_action('load-'.$my_admin_page, array( $this, 'converse_add_help_tab') );
		}

function chatme_admin(){ ?>
	<div class="wrap">
		<h2>ChatMe</h2>
		<p><a href="http://chatme.im" target="_blank">www.chatme.im</a></p>
	</div>
<?php }

	function register_converse_mysettings() {
	//register our settings
		register_setting('converse_options_list', 'language');
		register_setting('converse_options_list', 'bosh');
		register_setting('converse_options_list', 'call');
		register_setting('converse_options_list', 'carbons');
		register_setting('converse_options_list', 'foward');
		register_setting('converse_options_list', 'providers_link');
		register_setting('converse_options_list', 'placeholder');
		register_setting('converse_options_list', 'panel');
		register_setting('converse_options_list', 'custom');
		register_setting('converse_options_list', 'bosh_type');
		register_setting('converse_options_list', 'clear'); 
		register_setting('converse_options_list', 'emoticons'); 
		register_setting('converse_options_list', 'toggle_participants');		
		register_setting('converse_options_list', 'play_sounds');
		register_setting('converse_options_list', 'sounds_path'); 

		register_setting('converse_options_list', 'xhr_user_search');
		register_setting('converse_options_list', 'prebind');
		register_setting('converse_options_list', 'hide_muc_server');
		register_setting('converse_options_list', 'auto_list_rooms');
		register_setting('converse_options_list', 'auto_subscribe');
		}

	function converse_options() {
  		if (!current_user_can('manage_options'))  {
    	wp_die( __('You do not have sufficient permissions to access this page.', 'conversejs') );
  		}
?>
<div class="wrap">
	<h2>ConverseJS</h2>
	<p><?php _e("For more information visit <a href='http://www.chatme.im' target='_blank'>www.chatme.im</a>", 'conversejs'); ?> - <?php _e('<a href="https://webchat.chatme.im/?r=support" target="_blank">Support Chat Room</a> - <a href="https://conversejs.org/" trget="_blank">ConverseJS.org</a></p> ', 'conversejs'); ?>

	<form method="post" action="options.php">
    	<?php settings_fields( 'converse_options_list' ); ?>
    	<table class="form-table">
        	<tr valign="top">
        		<th scope="row">
				<select id="language" name="bosh_type">
					<option value="bosh_service_url" <?php selected('bosh_service_url', get_option('bosh_type')); ?>><?php _e("Bosh Server", 'conversejs'); ?></option>
					<option value="websocket_url" <?php selected('websocket_url', get_option('bosh_type')); ?>><?php _e("WebSocket Server", 'conversejs'); ?></option>
				</select>
			</th>
        	<td>
        		<input id="bosh" name="bosh" type="url" placeholder="<?php _e("bosh/WS service", 'conversejs'); ?>" value="<?php echo get_option('bosh'); ?>"><br/><em><?php _e("We suggest: <br/>Bosh: https://bind.chatme.im<br />WebSocket: wss//ws.chatme.im", 'conversejs'); ?></em>
        	</td>
        	</tr> 
            
        	<tr valign="top">
        		<th scope="row"><?php _e("Provider Link", 'conversejs'); ?></th>
        	<td>
        		<input id="providers_link" name="providers_link" type="url" placeholder="<?php _e("provider link", 'conversejs'); ?>" value="<?php echo get_option('providers_link'); ?>"><br/><em><?php _e("We suggest http://chatme.im/servizi/domini-disponibili/", 'conversejs'); ?></em>
        	</td>
        	</tr> 
            
        	<tr valign="top">
        		<th scope="row"><?php _e("Register Placeholder", 'conversejs'); ?></th>
        	<td>
        		<input id="placeholder" name="placeholder" type="text" placeholder="<?php _e("register placeholder", 'conversejs'); ?>" value="<?php echo get_option('placeholder'); ?>"><br/><em><?php _e("We suggest e.g. chatme.im", 'conversejs'); ?></em>
        	</td>
        	</tr>                           

        	<tr valign="top">
        		<th scope="row"><?php _e("Visible Buttons", 'conversejs'); ?></th>
        		<td><?php _e("Enable Call Button", 'conversejs'); ?> <input type="checkbox" name="call" value="true" <?php checked('true', get_option('call')); ?> /> - <?php _e("Enable Clear Button", 'conversejs'); ?> <input type="checkbox" name="clear" value="true" <?php checked('true', get_option('clear')); ?> /> - <?php _e("Enable Emoticons", 'conversejs'); ?> <input type="checkbox" name="emoticons" value="true" <?php checked('true', get_option('emoticons')); ?> /> - <?php _e("Enable toggle participants Button", 'conversejs'); ?> <input type="checkbox" name="toggle_participants" value="true" <?php checked('true', get_option('toggle_participants')); ?> /></td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Hide Chat Panel Open", 'conversejs'); ?></th>
        		<td><input type="checkbox" name="panel" value="false" <?php checked('false', get_option('panel')); ?> /> <?php _e("Yes", 'conversejs'); ?></td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Sounds", 'conversejs'); ?></th>
        		<td><?php _e("Play Sounds", 'conversejs'); ?> <input type="checkbox" name="play_sounds" value="false" <?php checked('true', get_option('play_sounds')); ?> /><br/><?php _e("Sounds Path", 'conversejs'); ?><input id="placeholder" name="sounds_path" type="text" placeholder="<?php _e("./sounds", 'conversejs'); ?>" value="<?php echo get_option('sounds_path'); ?>"><br/><em><?php _e("sound patch ./sounds work with mp3 and odg", 'conversejs'); ?></em></td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Enable Messages Carbons", 'conversejs'); ?></th>
        		<td><input type="checkbox" name="carbons" value="true" <?php checked('true', get_option('carbons')); ?> /> <?php _e("Yes", 'conversejs'); ?></td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Enable Foward Messages", 'conversejs'); ?></th>
        		<td><input type="checkbox" name="foward" value="true" <?php checked('true', get_option('foward')); ?> /> <?php _e("Yes", 'conversejs'); ?></td>
        	</tr> 

        	<tr valign="top">
        		<th scope="row"><?php _e('Custom Variable <br/> More info <a href="https://conversejs.org/docs/html/configuration.html#configuration-variables" target="_blank">Here.</a><br/>Not Overwrite the varables managed from other options.', 'conversejs'); ?></th>
        		<td><textarea name="custom" rows="4" cols="50"><?php echo esc_js(get_option('custom')); ?></textarea></td>
        	</tr> 
    
        	<tr valign="top">
        		<th scope="row"><?php _e("Converse language", 'conversejs'); ?></th>
        	<td>
        		<select id="language" name="language">
        			<option value="de" <?php selected('de', get_option('language')); ?>>Deutsch</option>
        			<option value="en" <?php selected('en', get_option('language')); ?>>English</option>
        			<option value="es" <?php selected('es', get_option('language')); ?>>Espanol</option>
        			<option value="fr" <?php selected('fr', get_option('language')); ?>>Francais</option>
        			<option value="it" <?php selected('it', get_option('language')); ?>>Italiano</option>
        			<option value="ja" <?php selected('ja', get_option('language')); ?>>Ja</option>
        			<option value="nl" <?php selected('nl', get_option('language')); ?>>Nederlands</option>
        			<option value="ru" <?php selected('ru', get_option('language')); ?>>Ru</option>
        		</select>
        	</td>
        </tr>
    </table>
    
	<?php submit_button(); ?>

    <p><?php _e('For Ever request you can use our <a href="http://chatme.im/forums" target="_blank">forum</a>', 'conversejs') ?></p>

</form>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="8CTUY8YDK5SEL">
	<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
	<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>

	<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://chatme.im" data-text="Visita chatme.im" data-via="chatmeim" data-lang="it">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>
<?php 
	}
} 
new converseJS;
?>