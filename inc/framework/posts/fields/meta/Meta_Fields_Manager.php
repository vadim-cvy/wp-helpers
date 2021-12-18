<?php

namespace JMCG\inc\framework\posts\fields\meta;

use \JMCG\inc\framework\posts\fields\Fields_Manager;
use \JMCG\inc\framework\posts\fields\Field_Descriptor;

if ( ! defined( 'ABSPATH' ) ) exit;

class Meta_Fields_Manager extends Fields_Manager
{
    protected function get_field_value( Field_Descriptor $descriptor )
    {
        return $this->get_meta( $descriptor->get_key() );
    }

    public function get_unsafe( string $meta_key )
    {
        return $this->get_meta( $meta_key );
    }

    protected function get_meta( string $meta_key )
    {
        return get_post_meta( $this->post_id, $meta_key, true );
    }

    protected function update_field_value( Field_Descriptor $descriptor, $value ) : void
    {
        $this->update_meta( $descriptor->get_key(), $value );
    }

    public function update_unsafe( string $meta_key, $value )
    {
        return $this->update_meta( $meta_key, $value );
    }

    protected function update_meta( string $meta_key, $value )
    {
        update_post_meta( $this->post_id, $meta_key, $value );
    }

    public function get_descriptor( string $descriptor_alias ) : Field_Descriptor
    {
        $descriptor = parent::get_descriptor( $descriptor_alias );

        if ( ! is_a( $descriptor, Meta_Field_Descriptor::class ) )
        {
            throw new Exception(
                'Descriptor must be instance of ' . Meta_Field_Descriptor::class
            );
        }

        return $descriptor;
    }
}