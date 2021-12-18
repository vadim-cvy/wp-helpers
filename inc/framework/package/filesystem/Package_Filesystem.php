<?php

namespace JMCG\inc\framework\package\filesystem;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Package_Filesystem
{
    public static function get_root_dir() : Package_Dir
    {
        $namespace_parts = explode( '\\', __NAMESPACE__ );

        // Steps back number required to get back to the root dir.
        $steps_back_number = count( $namespace_parts ) - 1;

        $dir_path = __DIR__ . '/' . str_repeat( '../', $steps_back_number );

        return new Package_Dir( $dir_path );
    }

    public static function get_main_file() : Package_File
    {
        return static::get_root_dir()->get_file( 'Package.php' );
    }

    public static function get_templates_dir() : Package_Dir
    {
        return static::get_root_dir()->get_sub_dir( 'templates' );
    }

    public static function get_assets_dir() : Package_Dir
    {
        return static::get_root_dir()->get_sub_dir( 'assets/build' );
    }
}