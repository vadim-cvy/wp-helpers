<?php

namespace JMCG\inc\framework\posts\fields;

use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Fields_Manager
{
    protected $post_id;

    protected $registered_descriptors = [];

    public function __construct( int $post_id )
    {
        $this->post_id = $post_id;
    }

    public function register_descriptors( array $field_descriptors )
    {
        foreach ( $field_descriptors as $alias => $descriptor )
        {
            $this->register_descriptor( $alias, $descriptor );
        }
    }

    public function register_descriptor( string $descriptor_alias, Field_Descriptor $descriptor )
    {
        if ( ! empty( $this->registered_descriptors[ $descriptor_alias ] ) )
        {
            throw new Exception( 'Alias "' . $descriptor_alias . '" is already in use!' );
        }

        $this->registered_descriptors[ $descriptor_alias ] = $descriptor;
    }

    public function get( string $descriptor_alias )
    {
        $descriptor = $this->get_descriptor( $descriptor_alias );

        $value = $this->get_field_value( $descriptor );

        if ( isset( $value ) )
        {
            if ( $descriptor->get_type() )
            {
                settype( $value, $descriptor->get_type() );
            }
        }
        else
        {
            $value = $descriptor->get_default_value();
        }

        return $value;
    }

    public function update( string $descriptor_alias, $value ) : void
    {
        $descriptor = $this->get_descriptor( $descriptor_alias );

        $this->update_field_value( $descriptor, $value );
    }

    public function get_descriptor( string $descriptor_alias ) : Field_Descriptor
    {
        if ( empty( $this->registered_descriptors[ $descriptor_alias ] ) )
        {
            throw new Exception(
                'Alias "' . $descriptor_alias . '" is not registered! ' .
                'Use ' . static::class . '::register_descriptor() to register ' .
                    'needed field under this alias.'
            );
        }

        return $this->registered_descriptors[ $descriptor_alias ];
    }

    abstract protected function get_field_value( Field_Descriptor $descriptor );

    abstract protected function update_field_value( Field_Descriptor $descriptor, $value );
}