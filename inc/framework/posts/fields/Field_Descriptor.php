<?php

namespace JMCG\inc\framework\posts\fields;

use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Field_Descriptor
{
    protected $type = '';

    protected $default_value = null;

    public function set_type( string $type ) : void
    {
        if ( ! empty( $this->type ) )
        {
            throw new Exception( 'Field type has already been set!' );
        }

        $allowed_types = [
            'array',
            'string',
            'int',
            'float',
        ];

        if ( ! in_array( $type, $allowed_types ) )
        {
            throw new Exception(
                '"' . $type . '" is not allowed! ' .
                'Allowed types are: ' . implode( ', ', $allowed_types ) . '.'
            );
        }

        $this->type = $type;
    }

    public function get_type() : string
    {
        return $this->type;
    }

    public function get_defalut_value()
    {
        if ( isset( $this->default_value ) )
        {
            return $default_value;
        }
        else if ( $this->get_type() === 'array' )
        {
            return [];
        }
    }

    public function set_default_value( $default_value ) : void
    {
        if ( isset( $this->default_value ) )
        {
            throw new Exception( 'Field default value has already been set!' );
        }

        $this->default_value = $default_value;
    }
}