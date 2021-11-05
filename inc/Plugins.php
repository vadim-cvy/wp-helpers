<?php

namespace Cvy\helpers\inc;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Incapsulates common helpers for plugins related functionality.
 */
class Plugins
{
    /**
     * Wrapper for is_plugin_active().
     *
     * @param string $plugin_main_file  Plugin main file path.
     *                                  "{base dir name}/{main file name}.php"
     *
     * @return boolean                  True if plugin is active, false otherwise.
     */
    public static function is_active( string $plugin_main_file ) : bool
    {
        if ( ! function_exists( 'is_plugin_active' ) )
        {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        return is_plugin_active( $plugin_main_file );
    }

    /**
     * Checks if plugin is installed.
     *
     * @param string $plugin_main_file  Plugin main file path.
     *                                  "{base dir name}/{main file name}.php"
     *
     *  @return boolean                 True if plugin is installed, false otherwise.
     */
    public static function is_installed( string $plugin_main_file ) : bool
    {
        $file_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin_main_file;

        return file_exists( $file_path );
    }

    /**
     * Returns URL of the passed dir.
     *
     * @param string $dir_path Path to the dir.
     * @return string
     */
    public static function get_dir_url( string $dir_path ) : string
    {
        $dir_path = trailingslashit( $dir_path );

        /**
         * plugin_dir_url() removes last part from the path. We don't want target
         * dir name to be removed from the path. So we need to add something (a dot)
         * which will be removed instead.
         */
        $dir_path .= '.';

        return plugin_dir_url( $dir_path );
    }
}