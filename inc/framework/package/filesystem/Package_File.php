<?php

namespace JMCG\inc\framework\package\filesystem;

use \JMCG\inc\framework\filesystem\File;

if ( ! defined( 'ABSPATH' ) ) exit;

class Package_File extends File
{
    use tPackage_Filesystem_Object;
}