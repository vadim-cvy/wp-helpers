<?php

namespace JMCG\inc\framework\thirdparty_plugins;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Thirdparty_Plugin
{
    abstract static public function get_wrapped_instance() : Plugin;
}