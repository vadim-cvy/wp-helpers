<?php

namespace JMCG\inc\framework\posts\fields\meta;

use \JMCG\inc\framework\posts\fields\Field_Descriptor;
use \JMCG\inc\framework\package\Current_Package;

if ( ! defined( 'ABSPATH' ) ) exit;

class Meta_Field_Descriptor extends Field_Descriptor
{
    protected $key;

    public function __construct( string $meta_key, bool $add_package_prefix )
    {
        if ( $add_package_prefix )
        {
            $meta_key = Current_Package::get_slug() . '_' . $meta_key;
        }

        $this->key = $meta_key;
    }

    public function get_key() : string
    {
        return $this->key;
    }
}