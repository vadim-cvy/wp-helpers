<?php

namespace JMCG\inc\framework\package\data_storage;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class aPackage_Storage__Multi_Dimension extends aPackage_Storage
{
    abstract static public function set_data( array $data ) : void;

    abstract static public function get_data() : array;

    static public function get( string $data_item_key, $default_value = null )
    {
        $data = static::get_data();

        return isset( $data[ $data_item_key ] ) ?
            $data[ $data_item_key ] :
            $default_value;
    }

    static public function update( string $data_item_key, $value ) : void
    {
        static::update_multiple([
            $data_item_key => $value,
        ]);
    }

    static public function update_multiple( array $updated_data_items ) : void
    {
        $data = static::get_data();

        foreach ( $updated_data_items as $key => $value )
        {
            $data[ $key ] = $value;
        }

        static::set_data( $data );
    }
}
