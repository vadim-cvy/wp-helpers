<?php

namespace JMCG\inc\framework\hooks;

use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class aHook
{
    protected $name;

    public function __construct( $name )
    {
        $this->name = $name;
    }

    public function add_ensure( callable $callback, int $order = 10 ) : void
    {
        $this->validate_did( $order );

        $this->add( $callback, $order );
    }

    abstract protected function add( callable $callback, int $order ) : void;

    public function did( $order = 0 ) : bool
    {
        global $wp_actions;

        return did_action( $this->name ) && $wp_actions[ $this->name ] >= $order;
    }

    public function validate_did( int $order = 0 ) : void
    {
        if ( $this->did( $order ) )
        {
            throw new Exception(
                'Hook "' . $this->name . '" (order: ' . $order . ') has already fired!'
            );
        }
    }
}