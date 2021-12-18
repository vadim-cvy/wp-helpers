<?php

namespace JMCG\inc\framework\package\filesystem;

if ( ! defined( 'ABSPATH' ) ) exit;

class File extends aFilesystem_Object
{
    public function get_modified_time() : int
    {
        return filemtime( $this->get_path() );
    }

    public function require( ...$args ) : void
    {
        extract( $args );

        require $this->get_path();
    }

    public function require_once( ...$args ) : void
    {
        extract( $args );

        require_once $this->get_path();
    }
}