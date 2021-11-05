<?php

namespace JT\helpers\inc\acf\field\settings;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of the ACF field setting.
 *
 * This class is a singleton and may have only 1 instance.
 */
abstract class Field_Setting__Singleton
{
    use \JT\helpers\inc\design_pattern\tSingleton;

    protected function __construct()
    {
        $this->register();
    }

    /**
     * Returns setting name.
     *
     * @return string Setting name.
     */
    public function get_name() : string
    {
        return $this->get_args()['name'];
    }

    /**
     * Returns setting arguments.
     *
     * See 2nd param of acf_render_field_setting().
     *
     * @return array<mixed> Setting arguments.
     */
    abstract protected function get_args() : array;

    /**
     * Registers the setting.
     *
     * @return void
     */
    protected function register() : void
    {
        foreach ( $this->get_supported_field_types() as $field_type )
        {
            $action_name = 'acf/render_field_settings/type=' . $field_type;

            \JT\helpers\inc\WP_Hooks::add_action_ensure( $action_name, [ $this, '_print' ], 1 );
        }
    }

    /**
     * Returns field types which should have this setting.
     *
     * @return array<string> Field types.
     */
    abstract protected function get_supported_field_types() : array;

    /**
     * Prints the setting.
     *
     * @param   array $field_object ACF Field object (array).
     * @return  void
     */
    public function _print( array $field_object ) : void
    {
        acf_render_field_setting( $field_object, $this->get_args() );
    }
}
