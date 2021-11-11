<?php

namespace Cvy\helpers\inc\plugins;

use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with plugins.
 */
class Plugin
{
    /**
     * Plugin main file path relative to the plugins dir.
     *
     * Ex: "my-awesome-plugin/index.php".
     *
     * @var string
     */
    protected $main_file_relative_path = '';

    /**
     * @param string $main_file_relative_path See documentation of $this->main_file_relative_path.
     */
    public function __construct( string $main_file_relative_path )
    {
        $this->main_file_relative_path = $main_file_relative_path;
    }

    /**
     * Returns absolute path to the plugin main file.
     *
     * @return string
     */
    public function get_main_file_absolute_path() : string
    {
        return WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->main_file_relative_path;
    }

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

        return is_plugin_active( $this->main_file_relative_path );
    }

    /**
     * Throws error if plugin is active.
     *
     * @param boolean $cta If call to action text should be included.
     * @return boolean True if plugin is active, otherwise the error will be thrown.
     */
    public function validate_is_active( bool $cta = true ) : bool
    {
        $this->validate_is_installed( $cta );

        if ( $this->is_active() )
        {
            return true;
        }

        $error_message = $this->get_name() . ' plugin is not active!';

        if ( $cta )
        {
            $error_message .= ' Please activate it.';
        }

        throw new Plugin_Error( $error_message );
    }

    /**
     * Checks if plugin is installed.
     *
     *  @return boolean True if plugin is installed, false otherwise.
     */
    public function is_installed() : bool
    {
        return file_exists( $this->get_main_file_absolute_path() );
    }

    /**
     * Throws error if plugin is installed.
     *
     * @param boolean $cta If call to action text should be included.
     * @return boolean True if plugin is installed, otherwise the error will be thrown.
     */
    public function validate_is_installed( bool $cta = true ) : bool
    {
        if ( $this->is_installed() )
        {
            return true;
        }

        $error_message = $this->main_file_relative_path . ' plugin is not installed!';

        if ( $cta )
        {
            $error_message .= ' Please install it.';
        }

        throw new Plugin_Error( $error_message );
    }

    /**
     * Wrapper for get_plugin_data().
     *
     * @return array<string> Plugin data.
     */
    public function get_data() : array
    {
        return get_plugin_data( $this->get_main_file_absolute_path() );
    }

    /**
     * Getter for separate elements of the get_plugin_data().
     *
     * @param string $item_name Plugin data element name.
     * @return string           Value of the plugin data element.
     */
    public function get_data_item( string $item_name ) : string
    {
        return $this->get_data()[ $item_name ];
    }

    /**
     * Plugin version.
     *
     * @return string
     */
    public function get_version() : string
    {
        return $this->get_data_item( 'Version' );
    }

    /**
     * Plugin name.
     *
     * @return string
     */
    public function get_name() : string
    {
        return $this->get_data_item( 'Name' );
    }
}