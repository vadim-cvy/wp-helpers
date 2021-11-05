<?php

namespace Cvy\helpers\inc\acf\field\settings;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of the ACF field setting.
 *
 * This class may have many instances.
 */
class Field_Setting__Dynamic extends Field_Setting__Singleton
{
    /**
     * See $this->get_args() documentation.
     *
     * @var array<mixed>
     */
    protected $args = [];

    /**
     * A callback function for $this->is_available_for_field().
     *
     * See $this->is_available_for_field() documentation.
     *
     * @var callable
     */
    protected $is_available_for_field_callback = null;

    /**
     * @param string $args  See documentation of $this->args.
     * @param callable      See documentation of $this->is_available_for_field_callback.
     */
    public function __construct( string $args, callable $is_available_for_field_callback = null )
    {
        $this->args               = $args;
        $this->is_available_for_field_callback = $is_available_for_field_callback;

        parent::__construct();
    }

    /**
     * Checks if setting is available for specific field.
     *
     * This method is called for ALL fields one by one so you may consider if specific
     * field can / cannot have current setting based on the passed field object.
     *
     * @param   array $field_object ACF Field object (array).
     * @return  boolean             True if setting is available for passed field, false otherwise.
     */
    protected function is_available_for_field( array $field_object ) : bool
    {
        return call_user_func( $this->is_available_for_field_callback, $field_object );
    }

    /**
     * Returns setting arguments.
     *
     * See 2nd param of acf_render_field_setting().
     *
     * @return array<mixed> Setting arguments.
     */
    protected function get_args() : array
    {
        return $this->args;
    }
}
