<?php

namespace JMCG\inc\framework\posts\fields\acf;

use \Exception;

use \JMCG\inc\framework\posts\fields\Fields_Manager;
use \JMCG\inc\framework\posts\fields\Field_Descriptor;

use \JMCG\inc\framework\posts\fields\acf\field\Field;
use \JMCG\inc\framework\posts\fields\acf\field\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

class ACF_Fields_Manager extends Fields_Manager
{
    protected function get_field_value( Field_Descriptor $descriptor )
    {
        $field = $this->get_field_instance_by_descriptor( $descriptor );

        return $field->get_value();
    }

    protected function update_field_value( Field_Descriptor $descriptor, $value ) : void
    {
        $field = $this->get_field_instance_by_descriptor( $descriptor );

        $field->update( $value );
    }

    public function get_field_instance( string $descriptor_alias ) : Field
    {
        $descriptor = $this->get_descriptor( $descriptor_alias );

        return $this->get_field_instance_by_descriptor( $descriptor );
    }

    public function get_field_instance_unsafe( string $selector, string $selector_type ) : Field
    {
        if ( $selector_type === 'key' )
        {
            $field = Fields::get_by_key( $selector );
        }
        else if ( $selector_type === 'name' )
        {
            $field = Fields::get_by_key( $selector );
        }
        else
        {
            throw new Exception(
                'Wrong $selector_type argument passed: ' . $selector_type . '!'
            );
        }

        $field->set_context( $this->post_id );

        return $field;
    }

    protected function get_field_instance_by_descriptor( Field_Descriptor $descriptor ) : Field
    {
        $field = Fields::get_by_key( $descriptor->get_key() );

        $field->set_context( $this->post_id );

        return $field;
    }

    public function get_descriptor( string $descriptor_alias ) : Field_Descriptor
    {
        $descriptor = parent::get_descriptor( $descriptor_alias );

        if ( ! is_a( $descriptor, ACF_Field_Descriptor::class ) )
        {
            throw new Exception(
                'Descriptor must be instance of ' . ACF_Field_Descriptor::class
            );
        }

        return $descriptor;
    }
}