<?php

namespace JMCG\inc\framework\package;

use \Exception;

use \JMCG\inc\framework\hooks\Hooks;

use \JMCG\inc\framework\package\filesystem\Package_Filesystem;
use \JMCG\inc\framework\package\filesystem\Package_Dir;

use \JMCG\Main;

if ( ! defined( 'ABSPATH' ) ) exit;

class Package_Assets_Manager
{
    static public function add_internal_css( string $handle, string $asset_file_relative_url, array $extra_args = [] ) : void
    {
        $handle = static::maybe_prefix_handle( $handle );

        $asset_file =
            Package_Filesystem::get_assets_dir()
            ->get_sub_dir( 'css' )
            ->get_file( $asset_file_relative_url );

        $url = $asset_file->get_url();

        $extra_args['version'] = (string) $asset_file->get_time_modified();

        static::add_css( $handle, $url, $extra_args );
    }

    static public function add_css( string $handle, string $asset_file_url, array $extra_args = [] ) : void
    {
        $handle = static::maybe_prefix_handle( $handle );

        $extra_args = static::get_css_asset_extra_args( $extra_args );

        static::add_enqueue_scripts_callback(function() use ( $handle, $asset_file_url, $extra_args ) : void
        {
            $deps    = $extra_args['deps'];
            $version = $extra_args['version'];
            $media   = $extra_args['media'];

            wp_enqueue_style( $handle, $asset_file_url, $deps, $version, $media );
        });
    }

    static public function add_internal_js( string $handle, string $asset_file_relative_url, array $extra_args = [] ) : void
    {
        $handle = static::maybe_prefix_handle( $handle );

        $asset_file =
            Package_Filesystem::get_assets_dir()
            ->get_sub_dir( 'js' )
            ->get_file( $asset_file_relative_url );

        $url = $asset_file->get_url();

        $extra_args['version'] = (string) $asset_file->get_modified_time();

        static::add_js( $handle, $url, $extra_args );
    }

    static public function add_js( string $handle, string $asset_file_url, array $extra_args = [] ) : void
    {
        $handle = static::maybe_prefix_handle( $handle );

        $extra_args = static::get_js_asset_extra_args( $extra_args );

        static::add_enqueue_scripts_callback(function() use ( $handle, $asset_file_url, $extra_args ) : void
        {
            $deps      = $extra_args['deps'];
            $version   = $extra_args['version'];
            $in_footer = $extra_args['in_footer'];

            wp_enqueue_script( $handle, $asset_file_url, $dependencies, $version, $in_footer );
        });

        if ( $extra_args['is_module'] )
        {
            static::mark_script_as_module( $handle );
        }
    }

    static protected function mark_script_as_module( string $script_handle )
    {
        $required_handle = $script_handle;

        $filter = Hooks::get_filter( 'script_loader_tag' );

        $filter->add_callback(function( string $tag, string $handle ) use ( $required_handle ) : string
        {
            if ( $handle === $required_handle )
            {
                $tag = str_replace( '<script', '<script type="module"', $tag );
            }

            return $tag;
        });
    }

    static public function localize_internal_js_data( string $handle, string $object_name, array $data ) : void
    {
        $handle = static::maybe_prefix_handle( $handle );

        if ( ! wp_script_is( $handle, 'enqueued' ) )
        {
            throw new Exception(
                'Can\'t localize "' . $handle . '" data! ' .
                '"' . $handle . '" is not enqueued yet.'
            );
        }

        $object_name = Main::get_slug() . ucfirst( $object_name );

        static::add_enqueue_scripts_callback(function() use ( $handle, $object_name, $data )
        {
            wp_localize_script( $handle, $object_name, $data );
        });
    }

    static protected function add_enqueue_scripts_callback( callable $callback )
    {
        Hooks::get_action( 'wp_enqueue_scripts' )->add_callback( $callback );
        Hooks::get_action( 'admin_enqueue_scripts' )->add_callback( $callback );
    }

    static protected function maybe_prefix_handle( string $asset_handle ) : string
    {
        $prefix = Main::get_slug() . '_';

        $is_handle_prefixed = strpos( $asset_handle, $prefix ) === 0;

        if ( ! $is_handle_prefixed )
        {
            $asset_handle = $prefix . $asset_handle;
        }

        return $asset_handle;
    }

    static protected function get_css_asset_extra_args( array $custom_extra_args = [] ) : array
    {
        $defaults = [
            'deps'    => [],
            'media'   => '',
            'version' => ''
        ];

        return static::get_asset_extra_args( $defaults, $custom_extra_args );
    }

    static protected function get_js_asset_extra_args( array $custom_extra_args = [] ) : array
    {
        $defaults = [
            'deps'      => [],
            'version'   => '',
            'in_footer' => true,
            'is_module' => false,
        ];

        return static::get_asset_extra_args( $defaults, $custom_extra_args );
    }

    static protected function get_asset_extra_args( array $defaults, array $custom_extra_args ) : array
    {
        $extra_args = array_merge( $defaults, $custom_extra_args );

        $invalid_keys = array_diff_key( $defaults, $extra_args );

        if ( ! empty( $invalid_keys ) )
        {
            throw new Exception(
                'The following extra args keys are not valid: "' .
                implode( '", "', $invalid_keys ) . '"!'
            );
        }

        foreach ( $defaults as $key => $default_value )
        {
            $required_type = gettype( $default_value );
            $passed_type   = gettype( $extra_args[ $key ] );

            if ( $required_type !== $passed_type )
            {
                throw new Exception(
                    'The following extra arg type is not valid: "' . $key . '"! ' .
                    'Must be type of "' . $required_type . '" but "' . $passed_type . '" type passed.'
                );
            }
        }
    }
}