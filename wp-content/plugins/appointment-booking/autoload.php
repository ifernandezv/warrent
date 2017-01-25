<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @param $className
 */
function bookly_autoload( $className ) {
    $className = str_replace( '\\', DIRECTORY_SEPARATOR, $className );
    $paths = array(
        '/lib/payment/',
        '/lib/'
    );

    foreach ( $paths as $path ) {
        if ( is_readable( AB_PATH . $path . $className . '.php' ) ) {
            require_once( AB_PATH . $path . $className . '.php' );
        }
    }
}

spl_autoload_register( 'bookly_autoload' );