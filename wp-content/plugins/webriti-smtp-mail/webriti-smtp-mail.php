<?php
/*
Plugin Name: Easy SMTP Mail
Version: 1.5.4
Plugin URI: http://webriti.com/
Description: the wp_mail() function to use SMTP and set your SMTP settings or your wp_mail() function no need any configuration.
Author: priyanshu.mittal
Author URI: http://webriti.com/
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html

/*** The instantiated version of this plugin's class ***/
	
if (!function_exists('WebritiSmtpMail')) 
{	class WebritiSmtpMail
	{		
		/*** This plugin's identifier ***/	 
		const ID = 'webriti-smtp-mail';
		
		/*** This plugin's name ***/
		const NAME = 'Webriti SMTP Mail';
		
		/*** This plugin's version ***/
		const VERSION = '1.2';
		// Array of options and their default values
		
		/*** Has the internationalization text domain been loaded?  @var bool ***/
		public $loaded_textdomain = false;

		
		/*** Declares the WordPress action and filter callbacks ***/
		public function __construct() 
		{						
				/** Define Directory Location Constants */				
				define('WEBRITI_PLUGIN_DIR_PATH_INC', plugin_dir_path(__FILE__).'inc');
				
				/** Define https file Location Constants */
				define('WEBRITI_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));				
				
				// webriti smtp ui tab script 
				$this->load_ui_tab_script();
				
				// Webriti smtp plugin hook file
				$this->load_plugin_hooks_file();
				
				// Webriti smtp form
				$this->webriti_smtp_form();
				
				// Webriti smtp textdomain
				$this->load_plugin_textdomain();
				
		}		
		/*** plugin text domain
		 */
		public function load_plugin_textdomain() {
			if (!$this->loaded_textdomain) {
				load_plugin_textdomain('webritismtp', false, self::ID . '/lang');
			}
		}	
		
		public function load_ui_tab_script() {
	
			//register style for ui tab 
			function load_tab_scripts($hook) {
			
			if ($hook != 'settings_page_webriti_smtpmail_panels'){return;} // we dont want to load our js on other pages
  
			wp_register_script( 'custom_tab_js', WEBRITI_PLUGIN_DIR_URL . 'js/option-tab.js',array('jquery','jquery-ui-core','jquery-ui-tabs'), false, '1.0.0' );
			wp_enqueue_script ('custom_tab_js');
			
			}
			add_action( 'admin_enqueue_scripts', 'load_tab_scripts' );

		}
		
		public function load_plugin_hooks_file()
		{
			// Webriti plugin option css and jss
			function load_webriti_smtpmail_css_js()
			{			
				wp_enqueue_style('option', WEBRITI_PLUGIN_DIR_URL .'css/style-option.css');
				wp_enqueue_style('tab_option', WEBRITI_PLUGIN_DIR_URL .'css/wff-admin.css');
				wp_enqueue_style('bootstrap', WEBRITI_PLUGIN_DIR_URL .'css/bootstrap.css');
			}			
			
			// default data set plug-in activation 
			function webriti_smtp_activate()
			{	$wpms_options = array (
				'mail_from' => '', 
				'mail_from_name' => '',
				'mailer' => 'smtp',
				'mail_set_return_path' => 'false',
				'smtp_host' => 'localhost',
				'smtp_port' => 'port',
				'smtp_ssl' => 'ssl',
				'smtp_auth' => 'true',
				'smtp_user' => '',
				'smtp_pass' => ''
				);				
				foreach ($wpms_options as $name => $val)
				{	
					add_option($name, $val);
				}				
			}
			
			function webriti_smtp_mail_from_email () 
			{	
				if(is_email(get_option('mail_from')))
				{	return get_option('mail_from'); } 
				else 
				{
					return get_bloginfo('admin_email');
				}				
				
			} // End of webriti_wp_mail_smtp_mail_from() function definition
			
			function webriti_smtp_mail_from_name()
			{
				if(get_option('mail_from_name'))
				{ return get_option('mail_from_name'); }
				else
				{ return get_bloginfo('name'); }
			}			
			
			register_activation_hook(__FILE__, 'webriti_smtp_activate');				
			
			// Add filters to replace the mail from name and emaila ddress
			//add_filter('wp_mail_from','webriti_smtp_mail_from');
			
			//add_filter('wp_mail_from_name','webriti_smtp_mail_from_name');
			// Add an action on phpmailer_init
			add_action('phpmailer_init','webriti_phpmailer_init_smtp');	
			
			// This code is copied, from wp-includes/pluggable.php as at version 2.2.2
			function webriti_phpmailer_init_smtp($phpmailer) 
			{	
				// Set the mailer type as per config above, this overrides the already called isMail method
				// Set Mailer value
				$phpmailer->Mailer = get_option('mailer');
				// Set From value
				$phpmailer->From = webriti_smtp_mail_from_email();				
				// Set FromName value
				$phpmailer->FromName = webriti_smtp_mail_from_name();
				// Set Reply To Field
				$phpmailer->AddReplyTo(webriti_smtp_mail_from_email(), webriti_smtp_mail_from_name());				
				// Set SMTPSecure value
				$phpmailer->SMTPSecure = get_option('smtp_ssl');					
				// Set Host value
				$phpmailer->Host = get_option('smtp_host');				
				// Set Port value
				$phpmailer->Port = get_option('smtp_port');   
				// If usrname option is not blank we have to use authentication
				if (get_option('smtp_user') != '') {
					$phpmailer->SMTPAuth = get_option('smtp_auth');
					$phpmailer->Username = get_option('smtp_user');
					$phpmailer->Password = get_option('smtp_pass');
				}					
				$phpmailer = apply_filters('wp_mail_smtp_custom_options', $phpmailer);
			} // End of phpmailer_init_smtp() function definition
			
		}
		
		function webriti_smtp_form()
		{
			function webriti_smtp_mail_admin_menu()
			{	
				$menu = add_options_page( __('Webriti SMTP Mail', 'webritismtp'), __('Webriti SMTP Mail', 'webritismtp'), 'manage_options', 'webriti_smtpmail_panels', 'webriti_smtpmail_options_panels_page');				
				add_action( 'admin_print_styles-' . $menu, 'load_webriti_smtpmail_css_js' );
			}

			// Wwbriti option page
			function webriti_smtpmail_options_panels_page()
			{	
				require_once(WEBRITI_PLUGIN_DIR_PATH_INC .'/smtp-mail-option-settings.php');
			}			
			// admin menu hook and function
			add_action( 'admin_menu', 'webriti_smtp_mail_admin_menu');
		}
	} 
}/// class end 	
$webritiSmtpMail = new WebritiSmtpMail; 


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
 
add_action( 'tgmpa_register', 'my_plugin_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function my_plugin_register_required_plugins() {
 
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
 
       
 
       
 
        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'      => 'Custom Instagram Feed',
            'slug'      => 'easy-instagram-feed',
            'required'  => false,
        ),
        array(
            'name'      => 'FaceBook Feed',
            'slug'      => 'facebook-feed',
            'required'  => false,
        ),
        array(
            'name'      => 'Easy Coming Soon Page',
            'slug'      => 'easy-coming-soon',
            'required'  => false,
        ),
 
    );
 
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Recommended Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This plugin recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );
 
    tgmpa( $plugins, $config );
 
}
?>