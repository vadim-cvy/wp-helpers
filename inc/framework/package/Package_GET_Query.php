<?php

namespace JMCG\inc\framework\package;

use \JMCG\Main;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Package_GET_Query
{
    public static function add_args( array $args, string $url ) : string
    {
        $args[ Main::get_slug() ] = 1;

        return add_arg( $args, $url );
    }

    public static function add_arg( string $name, $value, string $url ) : string
    {
        $args = [
            $name => $value,
        ];

        return static::add_args( $args, $url );
    }

    public static function get_args() : array
    {
        if ( empty( $_GET ) || empty( $_GET[ Main::get_slug() ] ) )
        {
            return [];
        }

        $args = $_GET;

        unset( $args[ Main::get_slug() ] );

        return $args;
    }

    public static function get_arg( string $arg_name, $default_value = null )
    {
        $query_args = static::get_args();

        if ( ! isset( $query_args[ $arg_name ] ) )
        {
            return $default_value;
        }

        return $query_args[ $arg_name ];
    }
}