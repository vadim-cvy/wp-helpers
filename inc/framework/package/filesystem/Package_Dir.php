<?php

namespace JMCG\inc\framework\package\filesystem;

use \JMCG\inc\framework\filesystem\Dir;
use \JMCG\inc\framework\filesystem\File;

if ( ! defined( 'ABSPATH' ) ) exit;

class Package_Dir extends Dir
{
    use tPackage_Filesystem_Object;

    protected function get_child(
        string $wrapper_class_name,
        string $child_relative_path,
        bool $validate_exists
    ) : aFilesystem_Object
    {
        if ( $wrapper_class_name === Dir::class )
        {
            $wrapper_class_name = Package_Dir::class;
        }
        else if ( $wrapper_class_name === File::class )
        {
            $wrapper_class_name = Package_File::class;
        }

        return parent::get_child( $wrapper_class_name, $child_relative_path, $validate_exists );
    }
}