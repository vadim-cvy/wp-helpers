<?php

namespace JMCG\inc\framework\package\data_storage;

if ( ! defined( 'ABSPATH' ) ) exit;

class Package_Cookies_Storage extends aPackage_Storage__One_Dimension
{
    static public function get_data_item( string $name, $default_value = null )
    {
        $name = static::get_package_prefix() . $name;

        return ! empty( $_COOKIE[ $name ] ) ? $_COOKIE[ $name ] : '';
    }

    static public function update_data_item( string $name, $value, int $expires = 0, string $path = '/' ) : void
    {
        $name = static::get_package_prefix() . $name;

        if ( $expires === 0 )
        {
            $year_seconds = 60 * 60 * 24 * 365;

            $expires = time() + ( $year_seconds * 5 );
        }

        setcookie( $name, $value, $expires, $path );
    }
}