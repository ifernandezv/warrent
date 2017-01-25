<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class AB_Plugin
{
    const version = '7.7.1';

    public static function activate( $register_hook = true )
    {
        $installer = new AB_Installer();
        $installer->install();
        $register_hook ? do_action( 'bookly_activate' ) : null;
    }

    public static function deactivate( $register_hook = true )
    {
        unload_textdomain( 'bookly' );
        $register_hook ? do_action( 'bookly_deactivate' ) : null;
    }

    public static function uninstall( $register_hook = true )
    {
        $installer = new AB_Installer();
        $installer->uninstall();
        $register_hook ? do_action( 'bookly_uninstall' ) : null;
    }

    public static function registerHooks()
    {
        if ( is_admin() ) {
            register_activation_hook( AB_PATH . '/main.php',   array( __CLASS__, 'activate' ) );
            register_deactivation_hook( AB_PATH . '/main.php', array( __CLASS__, 'deactivate' ) );
            register_uninstall_hook( AB_PATH . '/main.php',    array( __CLASS__, 'uninstall' ) );

            new AB_PluginUpdateChecker(
                'http://booking-wp-plugin.com/index.php',
                AB_PATH . '/main.php',
                basename( AB_PATH ),
                24
            );
        }
    }
}