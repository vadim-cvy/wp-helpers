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
        if ( did_action( $action_name ) )
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
        if ( did_action( $filter_name ) )
        {
            throw new \Exception( 'Can\'t handle add_filter()! Filter "' . $filter_name . '" has already fired.' );
        }

        add_filter( $filter_name, $callback, $order, $args );
    }
}