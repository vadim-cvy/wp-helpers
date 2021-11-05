<?php

namespace Cvy\helpers\inc\settings;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A wrapper for register_setting().
 */
abstract class Setting
{
    use \Cvy\helpers\inc\design_pattern\tSingleton;

    /**
     * Reflects if there are any errors during setting fields validation process.
     *
     * @var boolean
     */
    protected $has_validation_errors = false;

    protected function __construct()
    {
        \Cvy\helpers\inc\WP_Hooks::add_action_ensure( 'init', [ $this, '_register' ] );
    }

    /**
     * Getter for setting name.
     *
     * @return string Setting name.
     */
    abstract public function get_name() : string;

    /**
     * Getter for setting value.
     *
     * @return mixed Setting value.
     */
    public function get_value()
    {
        $output_value = [];

        $db_value = get_option( $this->get_name(), [] );

        foreach ( $this->get_default_value() as $field_name => $default_value )
        {
            $output_value[ $field_name ] =
                ! empty( $db_value[ $field_name ] ) ?
                $db_value[ $field_name ] :
                $default_value;
        }

        return $output_value;
    }

    /**
     * Getter for setting options group.
     *
     * @return string Options group.
     */
    protected function get_options_group() : string
    {
        return $this->get_parent_page()->get_slug();
    }

    /**
     * Getter for a parent settings page instance.
     *
     * @return Page Parent settings page instance.
     */
    abstract public function get_parent_page() : Page;

    /**
     * Getter for setting fields.
     *
     * @return array<Field> Setting fields.
     */
    abstract protected function get_fields() : array;

    /**
     * Getter for setting default value.
     *
     * @return mixed a default value of the setting.
     */
    protected function get_default_value()
    {
        $value = [];

        foreach ( $this->get_fields() as $field )
        {
            $value[ $field->get_id() ] = $field->get_default_value();
        }

        /**
         * Don't return array if setting contains only 1 field.
         */
        if ( count( $value ) === 1 )
        {
            $value = array_shift( $value );
        }

        return $value;
    }

    /**
     * Value update callback.
     *
     * This method is triggered on value update before changes are saved to DB.
     *
     * @param mixed $setting_value  A new value of the setting.
     *
     * @return mixed                Sanitized $setting_value in case there are no
     *                              validation errors and old value otherwise.
     */
    public function _on_value_update( $setting_value )
    {
        foreach ( $this->get_fields() as $field )
        {
            $field_value =
                isset( $setting_value[ $field->get_id() ] ) ?
                $setting_value[ $field->get_id() ] :
                null;

            try
            {
                $setting_value[ $field->get_id() ] = $field->on_value_update( $field_value );
            }
            catch ( Field_Validation_Error $error )
            {
                $this->has_validation_errors = true;
            }
        }

        if ( $this->has_validation_errors )
        {
            $setting_old_value = $this->get_value();

            $setting_value = $setting_old_value;
        }

        return $setting_value;
    }

    /**
     * Registers the setting.s
     *
     * @return void
     */
    public function _register() : void
    {
        register_setting(
            $this->get_options_group(),
            $this->get_name(),
            [
                'sanitize_callback' => [ $this, '_on_value_update' ],
                'default'           => $this->get_default_value(),
            ]
        );
    }
}
