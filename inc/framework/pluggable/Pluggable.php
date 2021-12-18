<?php

namespace JMCG\inc\framework\pluggable;

use \JMCG\inc\framework\filesystem\Dir;
use \JMCG\inc\framework\filesystem\File;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Pluggable
{
    protected $main_file;

    static public function get_by_main_file_relative_path( string $main_file_relative_path ) : Pluggable
    {
        $plugins_dir = new Dir( WP_PLUGIN_DIR );

        $main_file = $plugins_dir->get_file( $main_file_relative_path );

        return static::get_by_main_file_instance( $main_file );
    }

    static public function get_by_main_file_instance( File $main_file ) : Pluggable
    {
        return new static( $main_file );
    }

    protected function __construct( File $main_file )
    {
        $this->main_file = $main_file;
    }

    public function get_main_file_relative_path() : string
    {
        return basename( $this->main_file->get_path(), $this->main_file->get_name() );
    }

    /**
     * Checks if plugin/theme is active.
     *
     * @return boolean True if is active, false otherwise.
     */
    abstract public function is_active() : bool;

    /**
     * Throws error if plugin/theme is not active.
     *
     * @param boolean $cta If call to action text should be included.
     * @return void
     */
    public function validate_is_active( bool $cta = true ) : void
    {
        $this->validate_is_installed( $cta );

        if ( $this->is_active() )
        {
            return;
        }

        $error_message = $this->get_name() . ' plugin is not active!';

        if ( $cta )
        {
            $error_message .= ' Please activate it.';
        }

        throw new Pluggable_Error( $error_message, 'not_active' );
    }

    /**
     * Checks if plugin/theme is installed.
     *
     *  @return boolean True if plugin/theme is installed, false otherwise.
     */
    abstract public function is_installed() : bool;

    /**
     * Throws error if plugin/theme is not installed.
     *
     * @param boolean $cta If call to action text should be included.
     * @return void
     */
    public function validate_is_installed( bool $cta = true ) : void
    {
        if ( $this->is_installed() )
        {
            return;
        }

        $error_message = $this->main_file_relative_path . ' plugin is not installed!';

        if ( $cta )
        {
            $error_message .= ' Please install it.';
        }

        throw new Pluggable_Error( $error_message, 'not_installed' );
    }

    /**
     * Wrapper for get_plugin_data() / get_theme_data().
     *
     * @return array<string> Plugin/theme data.
     */
    abstract public function get_data() : array;

    /**
     * Returns certain row of $this->get_data().
     *
     * @param string $row_name Plugin/theme data row name.
     * @return string          Value of the row.
     */
    protected function get_data_row( string $row_name ) : string
    {
        return $this->get_data()[ $row_name ];
    }

    /**
     * Plugin/theme version.
     *
     * @return string
     */
    public function get_version() : string
    {
        return $this->get_data_row( 'Version' );
    }

    /**
     * Plugin/theme name.
     *
     * @return string
     */
    public function get_name() : string
    {
        return $this->get_data_row( 'Name' );
    }

    /**
     * Plugin/theme text domain.
     *
     * @return string
     */
    public function get_text_domain() : string
    {
        return $this->get_data_row( 'TextDomain' );
    }

    abstract public function get_url_by_path( string $path ) : string;
}