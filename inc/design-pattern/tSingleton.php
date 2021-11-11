<?php

namespace Cvy\helpers\inc\design_pattern;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Software design pattern.
 */
trait tSingleton
{
    /**
     * Contains instences of the singletons created by $this->get_instance().
     *
     * @var array<tSingleton>
     */
    protected static $instances = [];

    /**
     * Instance getter.
     *
     * @return object An instance of the called class.
     */
    public static function get_instance() : object
    {
        $class = get_called_class();

        if ( ! isset( static::$instances[ $class ] ) )
        {
            static::$instances[ $class ] = static::create_instance();
        }

        return static::$instances[ $class ];
    }

    /**
     * Creates an instance of the called class.
     *
     * @return object Instance of the called class.
     */
    protected static function create_instance()
    {
        return new static();
    }

    protected function __construct() {}
}