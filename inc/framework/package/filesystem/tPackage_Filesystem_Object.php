<?php

namespace JMCG\inc\framework\package\filesystem;

use \JMCG\Main;

if ( ! defined( 'ABSPATH' ) ) exit;

trait tPackage_Filesystem_Object
{
    public function get_url() : string
    {
        return Main::get_wrapped_pluggable()->get_url_by_path( $this->get_path() );
    }
}