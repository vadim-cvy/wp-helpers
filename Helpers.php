<?php

namespace Cvy\helpers;

use \Cvy\helpers\inc\Plugins;
use \Cvy\helpers\inc\package\Package_Assets_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helpers package main file.
 */
class Helpers extends \Cvy\helpers\inc\package\Package
{
    public function init_includes() : void
    {
        \Cvy\helpers\inc\dashboard\Dashboard::get_instance();
    }

    protected function enqueue_assets( Package_Assets_Manager $assets ) : void
    {
        if ( is_admin() )
        {
            $assets->enqueue_internal_css( 'dashboard', 'dashboard.css' );
        }
    }

    /**
     * Checks if the package is allowed to run.
     *
     * @return boolean True if package is allowed to run false otherwise.
     */
    protected function can_run() : bool
    {
        return true;
    }

    /**
     * Getter for the package root namespace.
     *
     * @return string Package root namespace.
     */
    protected function get_root_namespace() : string
    {
        return __NAMESPACE__;
    }

    /**
     * Getter for the helpers root(main) file path.
     *
     * @return string Helpers root(main) file path.
     */
    protected function get_root_file() : string
    {
        return __FILE__;
    }

    /**
     * Helpers version.
     *
     * @return string Helpers version.
     */
    public function get_version() : string
    {
        return \Cvy\Plugin::get_instance()->get_version();
    }

    /**
     * Returns URL of the passed dir.
     *
     * @param string $dir_path Path to the dir.
     * @return string
     */
    public function get_dir_url( string $dir_path ) : string
    {
        return Plugins::get_dir_url( $dir_path );
    }
}