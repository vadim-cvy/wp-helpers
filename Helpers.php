<?php

namespace Cvy\helpers;

use \Cvy\helpers\inc\package\Package;
use \Cvy\helpers\inc\package\Package_Assets_Manager;
use \Cvy\helpers\inc\dashboard\Dashboard;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helpers package main file.
 */
class Helpers extends Package
{
    public function init_includes() : void
    {
        Dashboard::get_instance();
    }

    protected function enqueue_assets( Package_Assets_Manager $assets ) : void
    {
        if ( is_admin() )
        {
            $assets->enqueue_internal_css( 'dashboard', 'dashboard.css' );
        }
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
    protected function get_main_file_path() : string
    {
        return __FILE__;
    }

    /**
     * Returns URL of the passed dir.
     *
     * @param string $dir_path Path to the dir.
     * @return string
     */
    public function get_dir_url( string $dir_path ) : string
    {
        return $this->get_parent_package()->get_dir_url( $dir_path );
    }

    /**
     * Customizes error message.
     *
     * @param string $error_message Initial error message.
     * @return string Customized error message.
     */
    public function prepare_dashboard_error_message( string $error_message ) : string
    {
        $this->get_parent_package()->prepare_dashboard_error_message( $error_message );
    }

    /**
     * Returns the main class of the package to which the helpers belong.
     *
     * @return Package
     */
    public function get_parent_package() : Package
    {
        if ( cvy_is_plugins_dir() )
        {
            return \Cvy\Plugin::get_instance();
        }
    }

    /**
     * Returns an array of plugins instances on which the package depends.
     *
     * Package will throw dashboard errors if some of the return plugins are not
     * active.
     *
     * @return array<\Cvy\helpers\inc\plugins\Plugin>
     */
    protected function get_dependable_plugins() : array
    {
        return [];
    }
}
