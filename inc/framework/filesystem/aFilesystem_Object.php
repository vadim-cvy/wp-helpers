<?php

namespace JMCG\inc\framework\filesystem;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class aFilesystem_Object
{
    protected $path;

    public function __construct( string $path )
    {
        $this->set_path( $path );
    }

    protected function set_path( string $path ) : void
    {
        $this->path = trailingslashit( $path );
    }

    public function get_path() : string
    {
        return $this->path;
    }

    public function get_name() : string
    {
        return basename( $this->get_path() );
    }

    public function exists() : bool
    {
        return file_exists( $this->get_path() );
    }

    public function validate_exists() : void
    {
        if ( ! $this->exists() )
        {
            throw new Exception(
                'Filesystem object does not exist! Path: ' . $this->get_path() . '.'
            );
        }
    }
}