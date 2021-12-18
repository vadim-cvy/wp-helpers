<?php

namespace JMCG\inc\framework\package;

use \JMCG\inc\framework\pluggable\Pluggable;
use \JMCG\inc\framework\pluggable\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class aPlugin_Package extends aPackage
{
    static public function get_wrapped_pluggable() : Pluggable
    {
        return static::get_wrapped_plugin();
    }

    static public function get_wrapped_plugin() : Plugin
    {
        static $plugin_object = null;

        if ( ! $plugin_object )
        {
            $plugin_object = Plugin::get_by_main_file_instance( Package_Filesystem::get_main_file() );
        }

        return $plugin_object;
    }
}