<?php

namespace Cvy\helpers\inc\settings;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Setting field.
 *
 * A wrapper for add_settings_field().
 *
 * May have only 1 instance.
 */
abstract class Field__Singleton
{
    use \Cvy\helpers\inc\design_pattern\tSingleton;

    /**
     * Adds required WP hooks.
     *
     * Must be called on field construct.
     */
    protected function __construct()
    {
        \Cvy\helpers\inc\WP_Hooks::add_action_ensure( 'admin_init', [ $this, '_register' ] );
    }

    /**
     * Getter for the field id.
     *
     * @return string Field id.
     */
    abstract public function get_id() : string;

    /**
     * Getter for the field parent setting.
     *
     * @return Setting Field parent setting.
     */
    abstract protected function get_setting() : Setting;

    /**
     * Getter for the field parent section.
     *
     * @return Section Field parent section.
     */
    abstract protected function get_section() : Section;

    /**
     * Getter for the field label.
     *
     * @return string Field label.
     */
    abstract protected function get_label() : string;

    /**
     * Getter for the field type.
     *
     * @return string Field type.
     */
    abstract protected function get_type() : string;

    /**
     * Checks if field is required.
     *
     * @return bool True if field is required, false otherwise.
     */
    abstract protected function is_required() : bool;

    /**
     * Getter for the field default value.
     *
     * @return mixed Field default value.
     */
    abstract public function get_default_value();

    /**
     * Getter for field input attributes.
     *
     * @return array<string,mixed> Field input attributes.
     */
    protected function get_input_attrs() : array
    {
        return [
            'type'     => $this->get_type(),
            'id'       => $this->get_id(),
            'name'     => $this->get_setting()->get_name() . '[' . $this->get_id() . ']',
            'value'    => esc_attr( $this->get_value() ),
            'required' => $this->is_required() ? 'required' : '',
        ];
    }

    /**
     * Getter for field value.
     *
     * @return mixed Field value.
     */
    public function get_value()
    {
        $setting_value = $this->get_setting()->get_value();

        if ( is_array( $setting_value ) )
        {
            return isset( $setting_value[ $this->get_id() ] ) ?
                $setting_value[ $this->get_id() ] :
                $this->get_default_value();
        }
        else
        {
            return $setting_value;
        }
    }

    /**
     * Value update callback.
     *
     * This method is triggered on value update before changes are saved to DB.
     *
     * @param mixed $value  A new value of the setting.
     *
     * @return mixed                Sanitized $value in case there are no validation
     *                              errors and old value otherwise.
     */
    public function on_value_update( $value )
    {
        $value = $this->sanitize( $value );

        $this->validate( $value );

        return $value;
    }

    /**
     * Sanitizes field value on update.
     *
     * @param mixed $value  Field new value.
     * @return mixed        Sanitized $value.
     */
    protected function sanitize( $value )
    {
        return trim( $value );
    }

    /**
     * Validates field value on update.
     *
     * Triggers error if field value is not valid.
     *
     * @param mixed $value  Field new value.
     * @return void
     */
    protected function validate( $value ) : void
    {
        if ( $this->is_required() && empty( $value ) )
        {
            $this->error( 'empty' );
        }
    }

    /**
     * Triggers validation error.
     *
     * Should be used on field value update.
     *
     * @param string $code  Error code.
     *                      Error code hint MUST be registered via
     *                      $this->get_error_code_hints(), otherwise script will
     *                      fail with an Exception.
     * @return void
     */
    protected function error( string $code )
    {
        $this->verify_error_code_exists( $code );

        $error_message =
            '"' . $this->get_section()->get_title() . '" section > ' .
            '"' . $this->get_label() . '" field: ' .
            'Invalid value!';

        $error = new Field_Validation_Error( $error_message );

        add_settings_error(
            $this->get_setting()->get_name(),
            $this->get_id() . '/' . $code,
            $error->getMessage()
        );

        throw $error;
    }

    /**
     * Throws an error if error code does not exist.
     *
     * @param string $code Error code.
     * @return void
     */
    protected function verify_error_code_exists( string $code ) : void
    {
        if ( ! isset( $this->get_error_code_hints()[ $code ] ) )
        {
            throw new \Exception(
                'Can\'t find a hint for "' . $code . '" error code. ' .

                'Please extend ' . self::class . '::get_error_code_hints() method in ' .
                get_called_class() . ' class. ' .

                get_called_class() . '::get_error_code_hints() should return an array containing ' .
                '"' . $code . '" => "{hint text}".'
            );
        }
    }

    /**
     * Returns field validation errors.
     *
     * @return array<string,string> Field validation errors.
     */
    protected function get_errors() : array
    {
        return get_settings_errors( $this->get_setting()->get_name() );
    }

    /**
     * Checks if field has validation errors.
     *
     * @return boolean True if field has validation errors, false otherwise.
     */
    protected function has_errors() : bool
    {
        return ! empty( $this->get_errors() );
    }

    /**
     * Returns an error code hint (description).
     *
     * @return string Error code hint.
     */
    protected function get_error_hint() : string
    {
        foreach ( $this->get_errors() as $error_data )
        {
            if ( $error_data['setting'] !== $this->get_setting()->get_name() )
            {
                continue;
            }

            $error_code_parts = explode( '/', $error_data['code'] );
            $setting_field_id = $error_code_parts[0];
            $code             = $error_code_parts[1];

            if ( $setting_field_id !== $this->get_id() )
            {
                continue;
            }

            if ( isset( $this->get_error_code_hints()[ $code ] ) )
            {
                return $this->get_error_code_hints()[ $code ];
            }
        }

        return '';
    }

    /**
     * Returns hints for all error codes available.
     *
     * @return array<string,string> Array of {code} => {hint}.
     */
    protected function get_error_code_hints() : array
    {
        return [
            'empty' => 'Field value can\'t be empty!'
        ];
    }

    /**
     * Renders the field.
     *
     * @return void
     */
    public function _render() : void
    {
        $templates_dir_path =
            \Cvy\helpers\Helpers::get_instance()->get_templates_dir();

        require $templates_dir_path . 'settings/field.php';
    }

    /**
     * Registers the field.
     *
     * @return void
     */
    public function _register() : void
    {
        add_settings_field(
            $this->get_id(),
            $this->get_label(),
            [ $this, '_render' ],
            $this->get_section()->get_parent_page()->get_slug(),
            $this->get_section()->get_id()
        );
    }
}