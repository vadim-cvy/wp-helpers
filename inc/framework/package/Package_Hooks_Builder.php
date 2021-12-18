<?php

namespace JMCG\inc\framework\package;

use \JMCG\inc\framework\hooks\Hooks_Builder;
use \JMCG\inc\framework\hooks\aHook;

use \JMCG\Main;

if ( ! defined( 'ABSPATH' ) ) exit;

class Package_Hooks_Builder extends Hooks_Builder
{
    protected function build_hook( string $type, string $name ) : aHook
    {
        $name = Main::get_slug() . '/' . $name;

        return parent::build_hook( $type, $name );
    }
}