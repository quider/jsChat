<?php
namespace ChatMe;

if ( ! defined( 'ABSPATH' ) ) exit;

/*
Plugin Name: ConverseJS
Plugin URI: https://conversejs.org/
Description: This plugin add the javascript code for Converse.js a Jabber/XMPP chat for your WordPress.
Version: 2.4.8
Author: camaran
Author URI: http://www.chatme.im
Text Domain: conversejs
Domain Path: /languages/
*/

class converseJS {
	
private $default 	= array(
				'languages' 			=> '/languages/',
				'language' 			    => 'en',	
				'webchat' 			    => 'https://bind.chatme.im/',
				'providers_link'		=> 'http://chatme.im/servizi/domini-disponibili/',
				'placeholder'			=> ' e.g. chatme.im',
				'call'				    => 'false',
				'carbons'			    => 'false',
				'foward'			    => 'false',
				'panel'				    => 'true',
				'conver'			    => '0.9.4',
				'custom'			    => '',
				'clear'				    => 'true', 
				'emoticons'			    => 'false', 
				'toggle_participants'		=> 'false', 
				'play_sounds'			=> 'false',
				'xhr_user_search'		=> 'false',
				'prebind'			=> 'false',
				'hide_muc_server'		=> 'false',
				'auto_list_rooms'		=> 'false',
		        	'auto_subscribe'		=> 'false',
				'bosh_type'			=> 'bosh_service_url',
				'sounds_path'			=> './sounds/',
				'plugin_options_key'		=> 'converseJS',
				'roster_groups'			=> 'false',
				'allow_otr'			=> 'false',
				'cdn'				=> 1,
				);

	function __construct() {
		add_action( 'wp_enqueue_scripts', 	array( $this, 'get_converse_head') );
		add_action( 'wp_footer', 		array( $this, 'get_converse_footer') );
		add_action( 'admin_menu', 		array( $this, 'converse_menu') );
		add_action( 'admin_init', 		array( $this, 'register_converse_mysettings') );
		add_action( 'init', 			array( $this, 'my_plugin_init') );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_action_converse_links') );
		}

	function my_plugin_init() {
      	$plugin_dir = basename(dirname(__FILE__));
      	load_plugin_textdomain( 'conversejs', false, $plugin_dir . $this->default['languages'] );
		}

      	function add_action_converse_links ( $links ) {
      	$mylinks = array( '<a href="' . admin_url( 'admin.php?page=' . $this->default['plugin_options_key'] ) . '">' . __( 'Settings', 'conversejs' ) . '</a>', );
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
		switch ($this->default['cdn']) {
			case 0:
				wp_register_style( 'ConverseJS', plugins_url( '/core/css/converse.min.css', __FILE__ ), array(), $this->default['conver'] );
				wp_enqueue_style( 'ConverseJS' );
				wp_register_script( 'ConverseJS', plugins_url( '/core/converse.min.js', __FILE__ ), array(), $this->default['conver'], false );
				wp_enqueue_script( 'ConverseJS' );
			case 1:
				wp_register_style( 'ConverseJScdn', 'https://cdn.chatme.im/chat/conversejs/css/converse.min.css', array(), 'cdn' );
				wp_enqueue_style( 'ConverseJScdn' );
				wp_register_script( 'ConverseJScdn', 'https://cdn.chatme.im/chat/conversejs/converse.min.js', array(), 'cdn', false );
				wp_enqueue_script( 'ConverseJScdn' );
		}
	}

	function get_converse_footer() {
		
		$setting = array(
				'language' 				=> esc_html(get_option('language')),	
				'webchat' 				=> esc_url(get_option('bosh')),
				'providers_link'		=> esc_url(get_option('providers_link')),
				'placeholder'			=> esc_html(get_option('placeholder')),
				'call'					=> esc_html(get_option('call')),
				'carbons'				=> esc_html(get_option('carbons')),
				'foward'				=> esc_html(get_option('foward')),
				'panel'					=> esc_html(get_option('panel')),	
				'custom'				=> wp_kses(get_option('custom'),''),	
				'clear'					=> esc_html(get_option('clear')), 
				'emoticons'				=> esc_html(get_option('emoticons')), 
				'toggle_participants'	=> esc_html(get_option('toggle_participants')), 
				'play_sounds'			=> esc_html(get_option('play_sounds')),
				'xhr_user_search'		=> esc_html(get_option('xhr_user_search')),
				'prebind'				=> esc_html(get_option('prebind')),
				'hide_muc_server'		=> esc_html(get_option('hide_muc_server')),
				'auto_list_rooms'		=> esc_html(get_option('auto_list_rooms')),
		        'auto_subscribe'		=> esc_html(get_option('auto_subscribe')),	
				'bosh_type'				=> esc_html(get_option('bosh_type')),
				'sounds_path'			=> esc_html(get_option('sounds_path')),
				'roster_groups'			=> esc_html(get_option('roster_groups')),
		);
						
		foreach( $setting as $k => $settings )
			if( false == $settings )
				unset( $setting[$k]);
						
		$actual = apply_filters( 'converse_actual', wp_parse_args( $setting, $this->default ) );
								
		$converse_html = printf( '
		
		<!-- Messenger -->
		<script>
			require([\'converse\'], function (converse) {
		    	converse.initialize({
		        	auto_list_rooms: %s,
		        	auto_subscribe: %s,
		        	%s: \'%s\',
		        	hide_muc_server: %s,
		        	i18n: locales[\'%s\'],
		        	prebind: %s,
		        	show_controlbox_by_default: %s,
		        	xhr_user_search: %s,		        		           
              			message_carbons: %s,
               			forward_messages: %s,
				domain_placeholder: "%s",
				providers_link: "%s",
				play_sounds: %s,
				sounds_path: \'%s\',
				roster_groups: %s,
				%s
				allow_otr: %s,
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
			$actual['roster_groups'],
			$actual['custom'],
			$actual['allow_otr'],
			$actual['call'],
			$actual['clear'], 
			$actual['emoticons'], 
			$actual['toggle_participants']
				);
                
        return apply_filters( 'converse_html', $converse_html );
                
	}

	function converse_menu() {
  		$my_admin_page = add_options_page( __('ConverseJS','conversejs'), __('ConverseJS','conversejs'), 'manage_options', $this->default['plugin_options_key'], array($this, 'converse_options') );
  		add_action('load-'.$my_admin_page, array( $this, 'converse_add_help_tab') );
		}

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
		register_setting('converse_options_list', 'roster_groups');
		register_setting('converse_options_list', 'hide_muc_server');
		register_setting('converse_options_list', 'allow_otr');

		register_setting('converse_options_list', 'xhr_user_search');
		register_setting('converse_options_list', 'prebind');
		register_setting('converse_options_list', 'auto_list_rooms');
		register_setting('converse_options_list', 'auto_subscribe');
		}

	function converse_options() {
  		if (!current_user_can('manage_options'))  {
    	wp_die( __('You do not have sufficient permissions to access this page.', 'conversejs') );
  		}
?>
<div class="wrap">
	<h1>ConverseJS</h1>
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
        		<input class="regular-text" aria-describedby="bosh-description" id="bosh" name="bosh" type="url" placeholder="<?php _e("bosh/WS service", 'conversejs'); ?>" value="<?php echo get_option('bosh'); ?>"><p class="description" id="bosh-description"><?php _e("We suggest: <br/>Bosh: https://bind.chatme.im<br />WebSocket: wss//ws.chatme.im", 'conversejs'); ?></p>
        	</td>
        	</tr> 
            
        	<tr valign="top">
        		<th scope="row"><label for="providers_link"><?php _e("Provider Link", 'conversejs'); ?></label></th>
        	<td>
        		<input class="regular-text" aria-describedby="link-description" id="providers_link" name="providers_link" type="url" placeholder="<?php _e("provider link", 'conversejs'); ?>" value="<?php echo get_option('providers_link'); ?>"><p class="description" id="link-description"><?php _e("We suggest http://chatme.im/servizi/domini-disponibili/", 'conversejs'); ?></p>
        	</td>
        	</tr> 
            
        	<tr valign="top">
        		<th scope="row"><label for="placeholder"><?php _e("Register Placeholder", 'conversejs'); ?></label></th>
        	<td>
        		<input class="regular-text" aria-describedby="placeholder-description" id="placeholder" name="placeholder" type="text" placeholder="<?php _e("register placeholder", 'conversejs'); ?>" value="<?php echo get_option('placeholder'); ?>"><p class="description" id="placeholder-description"><?php _e("We suggest e.g. chatme.im", 'conversejs'); ?></p>
        	</td>
        	</tr>                           

        	<tr valign="top">
        		<th scope="row"><?php _e("Visible Buttons", 'conversejs'); ?></th>
        		<td>
				<p><label for="call"><?php _e("Enable Call Button", 'conversejs'); ?> <input type="checkbox" id="call" name="call" value="true" <?php checked('true', get_option('call')); ?> /></label></p>
				<p><label for="clear"><?php _e("Enable Clear Button", 'conversejs'); ?> <input type="checkbox" id="clear" name="clear" value="true" <?php checked('true', get_option('clear')); ?> /></label></p>
				<p><label fro="emoticons"><?php _e("Enable Emoticons", 'conversejs'); ?> <input type="checkbox" id="emoticons" name="emoticons" value="true" <?php checked('true', get_option('emoticons')); ?> /></label></p>
				<p><label for="toggle_participants"><?php _e("Enable toggle participants Button", 'conversejs'); ?> <input type="checkbox" name="toggle_participants" id="toggle_participants" value="true" <?php checked('true', get_option('toggle_participants')); ?> /></label></p>
			</td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Hide Chat Panel Open", 'conversejs'); ?></th>
        		<td><label for="panel"><input type="checkbox" id="panel" name="panel" value="false" <?php checked('false', get_option('panel')); ?> /> <?php _e("Yes", 'conversejs'); ?></label></td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Sounds", 'conversejs'); ?></th>
        		<td><label for="play_sounds"><?php _e("Play Sounds", 'conversejs'); ?> <input type="checkbox" id="play_sounds" name="play_sounds" value="false" <?php checked('true', get_option('play_sounds')); ?> /></label><br/><label for="sounds_path"><?php _e("Sounds Path", 'conversejs'); ?><input aria-describedby="sounds-description" class="regular-text" id="sounds_path" name="sounds_path" type="text" placeholder="<?php _e("./sounds", 'conversejs'); ?>" value="<?php echo get_option('sounds_path'); ?>"></label><p class="description" id="sounds-description"><?php _e("sound patch ./sounds work with mp3 and odg", 'conversejs'); ?></p></td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Functions", 'conversejs'); ?></th>
        		<td>
				<p><label for="carbons"><?php _e("Enable Messages Carbons", 'conversejs'); ?> <input type="checkbox" name="carbons" id="carbons" value="true" <?php checked('true', get_option('carbons')); ?> /></label></p>
				<p><label for="foward"><?php _e("Enable Foward Messages", 'conversejs'); ?> <input type="checkbox" name="foward" id="foward" value="true" <?php checked('true', get_option('foward')); ?> /></label></p>
				<p><label for="hide_muc_server"><?php _e("Hide MUC Server", 'conversejs'); ?>  <input type="checkbox" name="hide_muc_server" id="hide_muc_server" value="true" <?php checked('true', get_option('hide_muc_server')); ?> /></label></p>
				<p><label for="allow_otr"><?php _e("Enable OTR", 'conversejs'); ?>  <input type="checkbox" name="allow_otr" id="allow_otr" aria-describedby="allow_otr-description" value="true" <?php checked('true', get_option('allow_otr')); ?> /></label></p>
				<p class="description" id="allow_otr-description"><?php _e("Enable OTR for more security chat conversations", 'conversejs'); ?></p>
			</td>
        	</tr>

        	<tr valign="top">
        		<th scope="row"><?php _e("Roster", 'conversejs'); ?></th>
        		<td><label for="roster_groups"><?php _e("Enable Roster Groups", 'conversejs'); ?> <input id="roster_groups" type="checkbox" name="roster_groups" value="true" <?php checked('true', get_option('roster_groups')); ?> /> </label></td>
        	</tr> 

        	<tr valign="top">
        		<th scope="row"><?php _e('Custom Variable <br/> More info <a href="https://conversejs.org/docs/html/configuration.html#configuration-variables" target="_blank">Here.</a><br/>Not Overwrite the varables managed from other options.', 'conversejs'); ?></th>
        		<td><textarea aria-describedby="custom-description" class="large-text code" name="custom" rows="4" cols="50"><?php echo wp_kses(get_option('custom'),''); ?></textarea><p class="description" id="custom-description"><?php _e('For advance use converse_html hook', 'conversejs'); ?></p></td>
        	</tr> 
    
        	<tr valign="top">
        		<th scope="row"><?php _e("Converse language", 'conversejs'); ?></th>
        	<td>
        		<select id="language" name="language">
        		<option value="de" <?php selected('de', get_option('language')); ?>><?php _e("Deutsch", 'conversejs'); ?></option>
        		<option value="en" <?php selected('en', get_option('language')); ?>><?php _e("English", 'conversejs'); ?></option>
        		<option value="es" <?php selected('es', get_option('language')); ?>><?php _e("Espa&ntilde;ol", 'conversejs'); ?></option>
        		<option value="fr" <?php selected('fr', get_option('language')); ?>><?php _e("Fran&ccedil;ais", 'conversejs'); ?></option>
        		<option value="it" <?php selected('it', get_option('language')); ?>><?php _e("Italiano", 'conversejs'); ?></option>
        		<option value="ja" <?php selected('ja', get_option('language')); ?>><?php _e("Japan", 'conversejs'); ?></option>
        		<option value="nl" <?php selected('nl', get_option('language')); ?>><?php _e("Nederlands", 'conversejs'); ?></option>
        		<option value="ru" <?php selected('ru', get_option('language')); ?>><?php _e("Russian", 'conversejs'); ?></option>
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
new \ChatMe\converseJS;
?>