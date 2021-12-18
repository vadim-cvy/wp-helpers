<?php

namespace JMCG;

use \JMCG\inc\framework\package\Plugin_Package;
use \JMCG\inc\framework\package\Package_Config;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/autoload.php';

class Main extends Plugin_Package
{
    static protected function init_includes() : void
    {

    }

    static protected function get_config() : Package_Config
    {
        return new Package_Config([
            'dependable_plugins' => [],
        ]);
    }
}

Main::init();