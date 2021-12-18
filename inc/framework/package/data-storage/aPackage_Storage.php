<?php

namespace JMCG\inc\framework\package\data_storage;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class aPackage_Storage
{
    abstract static public function get( string $data_item_key, $default_value = null );

    abstract static public function update( string $data_item_key, $value ) : void;

    static protected function get_package_prefix() : string
    {
        return \JMCG\Package::get_slug() . '_';
    }
}
