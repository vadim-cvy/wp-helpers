<?php

namespace JMCG\inc\framework\package;

use \Exception;

use \JMCG\inc\framework\design_patterns\tInitable;

use \JMCG\inc\framework\pluggable\Pluggable;
use \JMCG\inc\framework\pluggable\Pluggable_Error;

use \JMCG\inc\framework\dashboard\Dashboard;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class aPackage
{
    use tInitable;

    static protected function on_init() : void
    {
        if ( ! static::can_run() )
        {
            return;
        }

        static::init_includes();
    }

    static protected function can_run() : bool
    {
        $dependable_plugins = static::get_config()->get_item( 'dependable_plugins' );

        try
        {
            foreach ( $dependable_plugins as $plugin )
            {
                $plugin->validate_is_active();
            }
        }
        catch ( Pluggable_Error $error )
        {
            if (
                ! Pluggable_Error::is_code( 'not_active', $error ) &&
                ! Pluggable_Error::is_code( 'not_installed', $error )
            )
            {
                throw $error;
            }

            static::add_dashboard_error( $error->getMessage() );

            return false;
        }

        return true;
    }

    abstract static protected function init_includes() : void;

    abstract static protected function get_config() : Package_Config;

    static protected function get_type() : string
    {
        $wrapped_pluggable = $this->get_wrapped_pluggable();

        if ( is_subclass_of( $wrapped_pluggable, Plugin::class ) )
        {
            return 'plugin';
        }
        // else if ( is_subclass_of( $wrapped_pluggable, Theme::class ) )
        // {

        // }
        else
        {
            throw new Exception( 'Can\'t define package type!' );
        }
    }

    abstract static protected function get_wrapped_pluggable() : Pluggable;

    static public function get_slug() : string
    {
        $text_domain = static::get_wrapped_pluggable()->get_text_domain();

        if ( empty( $text_domain ) )
        {
            throw new Exception(
                'Text domain is missed! ' .
                'Add "Text Domain" row into main file header.'
            );
        }

        $slug = strtolower( $text_domain );

        return $slug;
    }

    static public function add_dashboard_error( string $error_message ) : void
    {
        $error_prefix =
            '<strong>' .
                '"' . static::get_wrapped_pluggable()->get_name() . '" ' .
                    ucfirst( static::get_type() ) . ' Error:' .
            '</strong> ';

        Dashboard::add_error( $error_prefix . $error_message );
    }
}