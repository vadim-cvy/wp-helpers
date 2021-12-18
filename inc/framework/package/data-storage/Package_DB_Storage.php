<?php

namespace JMCG\inc\framework\package\data_storage;

if ( ! defined( 'ABSPATH' ) ) exit;

class Package_DB_Storage extends aPackage_Storage__Multi_Dimension
{
    public function get_data() : array
    {
        return get_option( $this->get_option_name(), [] );
    }

    public function set_data( array $data ) : void
    {
        update_option( $this->get_option_name(), $data );
    }

    protected function get_option_name() : string
    {
        return static::get_package_prefix() . 'data';
    }
}