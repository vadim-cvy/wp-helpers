<?php

namespace JMCG\inc\framework\package;

use \Exception;

use \JMCG\inc\framework\thirdparty_plugins\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit;

class Package_Config
{
    /**
     * Config items.
     *
     * @var array<string,mixed>
     */
    protected $items = [];

    /**
     * @param array $config {prop name} => {prop value}.
     */
    public function __construct( array $config )
    {
        $this->set_items( $config );
    }

    /**
     * Returns config item value.
     *
     * @param string $item_name Item name.
     * @return mixed Item value.
     */
    public function get_item( string $item_name )
    {
        $possible_names = array_keys( $this->get_items_model() );

        if ( ! in_array( $item_name, $possible_names ) )
        {
            $this->error(
                'Wrong item name: "' . $item_name . '"! ' .
                'Possible names are: "' . implode( '", "', $possible_names ) . '".'
            );
        }

        return $this->items[ $item_name ];
    }

    /**
     * Setter for $this->items.
     *
     * @param array $items Config items.
     * @return void
     */
    protected function set_items( array $items ) : void
    {
        $this->validate_items( $items );

        $this->items = $items;
    }

    /**
     * Throws error if  passed config items are not valid.
     *
     * @param array $items Config items.
     * @return void
     */
    protected function validate_items( array $items ) : void
    {
        foreach ( $this->get_items_model() as $item_name => $item_model )
        {
            $allowed_types = $item_model['allowed_types'];

            if ( ! array_key_exists( $item_name, $items ) )
            {
                $this->item_error(
                    'Item is missed! ' .
                    'Take attention that allowed types for this item are: "' . implode( '", "', $allowed_types ) . '".'
                );
            }

            $item_value      = $items[ $item_name ];
            $item_value_type = gettype( $item_value );

            if ( ! in_array( $item_value_type, $allowed_types ) )
            {
                $this->item_error(
                    'Item type is wrong! ' .
                    'Allowed types are: "' . implode( '", "', $allowed_types ) . '".'
                );
            }

            $item_custom_validator_name = 'validate_item_value__' . $item_name;

            if ( method_exists( $this, $item_custom_validator_name ) )
            {
                $this->{$item_custom_validator_name}( $item_value );
            }
        }
    }

    /**
     * Throws error if passed value of "dependable_plugins" config item is invalid.
     *
     * @param array $item_value Value of the "dependable_plugins" config item.
     * @return void
     */
    protected function validate_item_value__dependable_plugins( array $item_value ) : void
    {
        foreach ( $item_value as $i => $plugin )
        {
            if ( ! is_a( $plugin, Plugin::class ) )
            {
                $this->item_error( 'dependable_plugins',
                    'All plugins must be children of "' . PLugin::class . '" class! ' .
                    'Element with key "' . $i . '" is not.'
                );
            }
        }
    }

    /**
     * Customizes error message based on the item name and throws the error.
     *
     * @param string $item_name Config item name.
     * @param string $error_message Error message that should be thrown.
     * @return void
     */
    protected function item_error( string $item_name, string $error_message ) : void
    {
        throw new Exception( 'Config item "' . $item_name . '" error! ' . $error_message );
    }

    /**
     * Throws error with passed error message.
     *
     * @param string $error_message Error message that should be thrown.
     * @return void
     */
    protected function error( string $error_message ) : void
    {
        throw new Exception( 'Config error! ' . $error_message );
    }

    /**
     * Returns data about config items structure.
     *
     * @return array<string<array<string,mixed>> Items structure.
     */
    protected function get_items_model() : array
    {
        $type_array = 'array';

        return [
            'dependable_plugins' => [
                'allowed_types' => [ $type_array ],
                'default_value' => [],
            ],
        ];
    }
}