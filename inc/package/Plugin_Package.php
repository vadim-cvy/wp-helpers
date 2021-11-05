<?php

namespace JT\helpers\inc\package;

use \JT\helpers\inc\Plugins;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A boilerplate for plugins main files.
 *
 * How to use:
 *
 * My_Awesome_Plugin extends \JT\helpers\inc\package\Plugin_Package
 * {
 *      // Your code goes here
 * }
 *
 * My_Awesome_Plugin::get_instance();
 */
abstract class Plugin_Package extends Package
{
    /**
     * Checks if the package is allowed to run.
     *
     * @return boolean True if package is allowed to run false otherwise.
     */
    protected function can_run() : bool
    {
        return $this->is_active();
    }

    /**
     * Wrapper for get_plugin_data().
     *
     * @return array<string> Plugin data.
     */
    public function get_plugin_data() : array
    {
        return get_plugin_data( $this->get_root_file() );
    }

    /**
     * Package version.
     *
     * @return string Package version.
     */
    public function get_version() : string
    {
        return $this->get_plugin_data()['Version'];
    }

    /**
     * Getter for plugin name.
     *
     * @return string Plugin name.
     */
    public function get_name() : string
    {
        return $this->get_plugin_data()['Name'];
    }

    /**
     * Checks if plugin is active.
     *
     * @return boolean True if plugin is active, false otherwise.
     */
    protected function is_active() : bool
    {
        $plugin =
            basename( $this->get_root_dir() ) . '/' .
            basename( $this->get_root_file() );

        return \JT\helpers\inc\Plugins::is_active( $plugin );
    }

    /**
     * Getter for the plugin root(main) file path.
     *
     * @return string Plugin root(main) file path.
     */
    abstract protected function get_root_file() : string;

    /**
     * Returns URL of the passed dir.
     *
     * @param string $dir_path Path to the dir.
     * @return string
     */
    public function get_dir_url( string $dir_path ) : string
    {
        return Plugins::get_dir_url( $dir_path );
    }

    /**
     * Adds dashboard error notice.
     *
     * @param string $error_message Notice message.
     * @return void
     */
    public function add_dashboard_error( string $error_message ) : void
    {
        $error_message =
            '<strong>' .
                '"' . $this->get_name() . '" Plugin Error:' .
            '</strong> ' .
            $error_message;

        parent::add_dashboard_error( $error_message );
    }
}