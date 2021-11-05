<?php

namespace JT\helpers\inc\acf;

use \JT\helpers\inc\Plugins;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Contains common ACF-based helpers.
 */
class ACF
{
    /**
     * Checks if ACF plugin is active.
     *
     * @return boolean True if plugin is active, false otherwise.
     */
    public static function is_active() : bool
    {
        return Plugins::is_active( static::get_plugin_main_file() );
    }

    /**
     * Checks if ACF plugin is installed.
     *
     *  @return boolean True if plugin is installed, false otherwise.
     */
    public static function is_installed() : bool
    {
        return Plugins::is_installed( static::get_plugin_main_file() );
    }

    /**
     * Getter for ACF plugin main file path (base dir + file name).
     *
     * @return string ACF plugin main file path.
     */
    protected static function get_plugin_main_file() : string
    {
        return 'advanced-custom-fields/acf.php';
    }
}