<?php

namespace JMCG\inc\framework\design_pattern;

if ( ! defined( 'ABSPATH' ) ) exit;

trait tInitable
{
    static $is_inited = false;

    public static function init() : void
    {
        if ( static::$is_inited )
        {
            return;
        }

        static::$is_inited = true;

        static::on_init();
    }

    abstract protected static function on_init() : void;
}