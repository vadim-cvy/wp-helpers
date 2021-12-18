<?php

namespace JMCG\inc\framework\thirdparty_plugins;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with Beaver Builder plugin data.
 */
class Plugin__Beaver_Builder extends Thirdparty_Plugin
{
    static public function get_wrapped_instance() : Plugin
    {
        return Plugin::get_by_main_file_relative_path( 'bb-plugin/fl-builder.php' );
    }

    static public function is_editor_screen() : bool
    {
        // Todo: maybe there is another more clear way.
        // This one may be buggy, ie ajax requests may be treated as editor screen.
        return isset( $_GET['fl_builder'] );
    }
}