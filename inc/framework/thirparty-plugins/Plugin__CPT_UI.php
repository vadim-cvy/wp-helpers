<?php

namespace JMCG\inc\framework\thirdparty_plugins;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to work with CPT UI (Custom Post Types UI) plugin data.
 */
class PLugin__CPT_UI extends Thirdparty_Plugin
{
    static public function get_wrapped_instance() : Plugin
    {
        return Plugin::get_by_main_file_relative_path( 'custom-post-type-ui/custom-post-type-ui.php' );
    }
}