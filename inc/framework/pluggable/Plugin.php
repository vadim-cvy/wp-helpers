<?php

namespace JMCG\inc\framework\pluggable;

use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with plugins.
 */
class Plugin extends Pluggable
{
    /**
     * Checks if plugin is active.
     *
     * @return boolean True if plugin is active, false otherwise.
     */
    public function is_active() : bool
    {
        if ( ! function_exists( 'is_plugin_active' ) )
        {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        return is_plugin_active( $this->get_main_file_relative_path() );
    }

    /**
     * Checks if plugin is installed.
     *
     *  @return boolean True if plugin is installed, false otherwise.
     */
    public function is_installed() : bool
    {
        return $this->main_file->exists();
    }

    /**
     * Wrapper for get_plugin_data().
     *
     * @return array<string> Plugin data.
     */
    public function get_data() : array
    {
        if ( ! function_exists( 'get_plugin_data' ) )
        {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        $markup = false;

        return get_plugin_data( $this->main_file->get_path(), $markup );
    }

    public function get_url_by_path( string $path ) : string;
    {
        $path = trailingslashit( $path );

        /**
         * plugin_dir_url() removes last part from the path. We don't want target
         * dir name to be removed from the path. So we need to add something (a dot)
         * which will be removed instead.
         */
        $path .= '.';

        return plugin_dir_url( $path );
    }
}