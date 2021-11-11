<?php

namespace Cvy\helpers\inc;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Incapsulates wrappers for wp hooks related funcitons.
 */
class WP_Hooks
{
    /**
     * A wrapper for add_action().
     *
     * @param string $action_name
     * @param callable|array $callback
     * @param integer $order
     * @param integer $args
     * @return void
     */
    public static function add_action_ensure(
        string $action_name,
        $callback,
        int $order = 10,
        int $args = 99
    ) : void
    {
        if ( static::did_action( $action_name, $order ) )
        {
            throw new \Exception( 'Can\'t handle add_action()! Action "' . $action_name . '" has already fired.' );
        }

        add_action( $action_name, $callback, $order, $args );
    }

    /**
     * A wrapper for add_filter().
     *
     * @param string $action_name
     * @param callable|array $callback
     * @param integer $order
     * @param integer $args
     * @return void
     */
    public static function add_filter_ensure(
        string $filter_name,
        $callback,
        int $order = 10,
        int $args = 99
    ) : void
    {
        if ( static::did_action( $filter_name, $order ) )
        {
            throw new \Exception( 'Can\'t handle add_filter()! Filter "' . $filter_name . '" has already fired.' );
        }

        add_filter( $filter_name, $callback, $order, $args );
    }

    /**
     * Checks if action/filter has already fired taking into account the order number.
     *
     * @param string $action_name Action/filter name.
     * @param integer $order Order of the action (number of the itteration).
     * @return bool True if action hasn't fired or current order number is less than $order arg,
     *              false otherwise.
     */
    protected static function did_action( string $action_name, int $order = 10 ) : bool
    {
        global $wp_actions;

        return did_action( $action_name ) && $wp_actions[ $action_name ] >= $order;
    }
}