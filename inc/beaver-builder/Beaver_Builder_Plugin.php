<?php

namespace Cvy\helpers\inc\beaver_builder;

use \Cvy\helpers\inc\plugins\Plugin_Singleton;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with Beaver Builder plugin data.
 */
class Beaver_Builder_Plugin extends Plugin_Singleton
{
    /**
     * Plugin main file path relative to the plugins dir.
     *
     * Ex: "my-awesome-plugin/index.php".
     *
     * @return string
     */
    protected static function get_main_file_relative_path() : string
    {
        return 'bb-plugin/fl-builder.php';
    }
}