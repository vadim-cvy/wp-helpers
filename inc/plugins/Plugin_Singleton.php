<?php

namespace Cvy\helpers\inc\plugins;

use \Cvy\helpers\inc\design_pattern\tSingleton;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with specific plugin.
 */
abstract class Plugin_Singleton
{
    use tSingleton;

    /**
     * Getter for plugin main file path relative to the plugins dir.
     *
     * Ex: "my-awesome-plugin/index.php".
     *
     * @return string
     */
    abstract protected static function get_main_file_relative_path() : string;

    /**
     * Creates an instance of the plugin.
     *
     * The instance will be accessable with static::get_instance().
     *
     * @return object Instance of the plugin.
     */
    protected static function create_instance() : Plugin
    {
        return new Plugin( static::get_main_file_relative_path() );
    }
}