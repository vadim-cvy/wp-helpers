<?php

namespace Cvy\helpers\inc\package;

use \Cvy\helpers\inc\plugins\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A boilerplate for plugins main files.
 *
 * How to use:
 *
 * My_Awesome_Plugin extends Plugin_Package
 * {
 *      // Your code goes here
 * }
 *
 * My_Awesome_Plugin::get_instance();
 */
abstract class Plugin_Package extends Package
{
    /**
     * Returns plugin instance to which the package is connected.
     *
     * @return Plugin
     */
    public function get_plugin_object() : Plugin
    {
        static $plugin_object = null;

        if ( ! $plugin_object )
        {
            $plugin_object = new Plugin(
                basename( $this->get_root_dir_path() ) . '/' .
                basename( $this->get_main_file_path() )
            );
        }

        return $plugin_object;
    }

    /**
     * Returns URL of the passed dir.
     *
     * @param string $dir_path Path to the dir.
     * @return string
     */
    public function get_dir_url( string $dir_path ) : string
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

    /**
     * Customizes error message.
     *
     * @param string $error_message Initial error message.
     * @return string Customized error message.
     */
    public function prepare_dashboard_error_message( string $error_message ) : string
    {
        return '<strong>' .
                '"' . $this->get_plugin_object()->get_name() . '" Plugin Error:' .
            '</strong> ' .
            $error_message;
    }
}