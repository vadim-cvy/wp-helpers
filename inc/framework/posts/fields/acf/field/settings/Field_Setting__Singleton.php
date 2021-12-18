<?php

namespace JMCG\inc\framework\posts\fields\acf\field\settings;

use \JMCG\inc\framework\design_pattern\tSingleton;

use \JMCG\inc\framework\hooks\Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of the ACF field setting.
 *
 * This class is a singleton and may have only 1 instance.
 */
abstract class Field_Setting__Singleton
{
    use tSingleton;

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

            Hooks::get_action( $action_name )->add_callback( [ $this, '_print' ], 1 );
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
