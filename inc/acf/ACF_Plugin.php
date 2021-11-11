<?php

namespace Cvy\helpers\inc\acf;

use \Cvy\helpers\inc\plugins\Plugin_Singleton;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with ACF plugin data.
 */
class ACF_Plugin extends Plugin_Singleton
{
    /**
     * Plugin main file path relative to the plugins dir.
     *
     * Ex: "my-awesome-plugin/index.php".
     *
     * @return string
     */
    protected static function get_main_file_path() : string
    {
        return 'advanced-custom-fields/acf.php';
    }
}