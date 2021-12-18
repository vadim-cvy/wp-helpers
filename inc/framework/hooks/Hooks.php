<?php

namespace JMCG\inc\framework\hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

class Hooks
{
    static public function get_filter( string $filter_name ) : Filter_Hook
    {
        return new Filter_Hook( $filter_name );
    }

    static public function get_action( string $action_name ) : Action_Hook
    {
        return new Action_Hook( $action_name );
    }
}