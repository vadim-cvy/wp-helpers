<?php

namespace JMCG\inc\framework\posts\fields\acf;

use \Throwable;
use \Exception;

use \JMCG\inc\framework\posts\fields\Field_Descriptor;

if ( ! defined( 'ABSPATH' ) ) exit;

class ACF_Field_Descriptor extends Field_Descriptor
{
    protected $key = '';

    public function __construct( string $field_key )
    {
        $this->key = $field_key;
    }

    public function get_key() : string
    {
        return $this->key;
    }

    public function get_db_name() : string
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT
                `meta_key`
            FROM
                `$wpdb->postmeta`
            WHERE
                `meta_value` = %s",
            [
                $this->get_key(),
            ]
        );

        $prefixed_name = $wpdb->get_var( $sql );

        $unprefixed_name = preg_replace( '~^_~', '', $prefixed_name );

        return $unprefixed_name;
    }

    public function get_selector() : string
    {
        try
        {
            return $this->get_key();
        }
        catch ( Throwable $error )
        {
            return $this->get_name();
        }
    }
}