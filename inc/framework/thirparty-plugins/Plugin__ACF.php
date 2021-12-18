<?php

namespace JMCG\inc\framework\thirdparty_plugins;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with ACF plugin data.
 */
class Plugin__ACF extends Thirdparty_Plugin
{
    static public function get_wrapped_instance() : Plugin
    {
        return Plugin::get_by_main_file_relative_path( 'advanced-custom-fields/acf.php' );
    }
}