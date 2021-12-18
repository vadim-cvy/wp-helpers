<?php

namespace JMCG\inc\framework\template_controllers;

use \JMCG\inc\framework\design_pattern\tSingleton;
use \JMCG\inc\framework\package\filesystem\Package_Dir;
use \JMCG\inc\framework\hooks\Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Template_Controller
{
    use tSingleton;

    protected function __construct()
    {
        Hooks::get_action( 'wp_enqueue_scripts' )->add_callback( [ $this, '_enqueue_assets' ], 9 );
    }

    public function _enqueue_assets() : void
    {
        $this->enqueue_assets();
    }

    protected function enqueue_assets() : void
    {

    }

    abstract public function render() : void;

    abstract public function get_templates_dir() : Package_Dir;
}