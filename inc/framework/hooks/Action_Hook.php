<?php

namespace JMCG\inc\framework\hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

class Action_Hook
{
    protected function add( callable $callback, int $order ) : void
    {
        add_action( $this->name, $callback, $order, 99 );
    }
}