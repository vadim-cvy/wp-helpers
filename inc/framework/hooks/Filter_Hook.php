<?php

namespace JMCG\inc\framework\hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

class Filter_Hook
{
    protected function add( callable $callback, int $order ) : void
    {
        add_filter( $this->name, $callback, $order, 99 );
    }
}