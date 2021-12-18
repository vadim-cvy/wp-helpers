<?php

namespace JMCG\inc\framework\package\filesystem;

if ( ! defined( 'ABSPATH' ) ) exit;

class Dir extends aFilesystem_Object
{
    public function get_file( string $relative_path, bool $validate_exists = true ) : File
    {
        return $this->get_child( File::class, $relative_path, $validate_exists );
    }

    public function get_sub_dir( string $relative_path, bool $validate_exists = true ) : Dir
    {
        return $this->get_child( Dir::class, $relative_path, $validate_exists );
    }

    protected function get_child(
        string $wrapper_class_name,
        string $child_relative_path,
        bool $validate_exists
    ) : aFilesystem_Object
    {
        $child_path = $this->get_path() . $child_relative_path;

        $child_object = new $wrapper_class_name( $child_path );

        if ( $validate_exists )
        {
            $child_object->validate_exists();
        }

        return $child_object;
    }
}