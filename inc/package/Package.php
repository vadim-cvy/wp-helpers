<?php

namespace Cvy\helpers\inc\package;

use \Cvy\helpers\inc\WP_Hooks;
use \Cvy\helpers\inc\design_pattern\tSingleton;
use \Cvy\helpers\inc\plugins\Plugin_Error;
use \Cvy\helpers\inc\dashboard\Dashboard;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Boilerplate for the package entry points.
 *
 * A package is an independent directory which may include own includes, templates,
 * assets, etc. The directory is assumed to be a package if it may function independently.
 * For example plugins and themes are supposed to be packages.
 * Another example: the Helpers package in which current file is loceted. Helpers
 * is an independent dirrectory and thus may be called a package.
 *
 * How to use:
 *
 * My_Awesome_Package extends Package
 * {
 *      // Your code goes here
 * }
 *
 * My_Awesome_Package::get_instance();
 */
abstract class Package
{
    use tSingleton
    {
        create_instance as tSingleton__create_instance;
    }

    /**
     * Creates an instance of the package.
     *
     * @return object Instance of the package.
     */
    static function create_instance() : Package
    {
        $instance = static::tSingleton__create_instance();

        $instance->run();

        return $instance;
    }

    /**
     * Assets manager.
     *
     * Helps to enqueue JS and CSS.
     *
     * @var Package_Assets_Manager
     */
    protected $assets_manager = null;

    protected function run() : void
    {
        if ( ! $this->can_run() )
        {
            return;
        }

        $this->init_includes();

        WP_Hooks::add_action_ensure( 'wp_enqueue_scripts', [ $this, '_callback__enqueue_scripts' ] );
        WP_Hooks::add_action_ensure( 'admin_enqueue_scripts', [ $this, '_callback__enqueue_scripts' ] );
    }

    /**
     * Inits includes (imports files with the main custom code).
     *
     * @return void
     */
    abstract protected function init_includes() : void;

    /**
     * Fires on "wp_enqueue_scripts" and "admin_enqueue_scripts" hooks.
     *
     * @return void
     */
    public function _callback__enqueue_scripts() : void
    {
        $this->enqueue_assets( $this->get_assets_manager() );
    }

    /**
     * Getter for $this->assets_manager.
     *
     * @return Package_Assets_Manager Instance of the assets manager.
     */
    public function get_assets_manager()
    {
        if ( ! $this->assets_manager )
        {
            $this->assets_manager = new Package_Assets_Manager( $this );
        }

        return $this->assets_manager;
    }

    /**
     * Enqueues assets.
     *
     * I.e call wp_enqueue_script() and wp_enqueue_style() here.
     * It is preffered to use such methods as $this->enqueue_internal_css_asset(),
     * $this->enqueue_css_asset(), etc instead of default wp functions.
     *
     * @return void
     */
    abstract protected function enqueue_assets( Package_Assets_Manager $assets ) : void;

    /**
     * Checks if the package is allowed to run.
     *
     * @return boolean True if package is allowed to run false otherwise.
     */
    protected function can_run() : bool
    {
        $is_error = false;

        try {
            foreach ( $this->get_dependable_plugins() as $plugin )
            {
                $plugin->validate_is_active();
            }
        }
        catch ( Plugin_Error $error )
        {
            $this->add_dashboard_error( $error->getMessage() );

            $is_error = true;
        }

        return empty( $is_error );
    }

    /**
     * Returns an array of plugins instances on which the package depends.
     *
     * Package will throw dashboard errors if some of the return plugins are not
     * active.
     *
     * @return array<\Cvy\helpers\inc\plugins\Plugin>
     */
    abstract protected function get_dependable_plugins() : array;

    /**
     * Getter for the package slug.
     *
     * Slug may be used for:
     * - database option names
     * - custom hook names
     * - css selectors
     * - etc
     *
     * @return string Package slug.
     */
    public function get_slug() : string
    {
        /**
         * My_Awesome_Package\helpers -> my_awesome_package_helpers
         */
        $slug = strtolower( $this->get_root_namespace() );
        $slug = str_replace( '\\', '_', $slug );

        return $slug;
    }

    /**
     * Getter for the package root namespace.
     *
     * @return string Package root namespace.
     */
    abstract protected function get_root_namespace() : string;

    /**
     * Getter for the package root(main) file path.
     *
     * @return string Package root(main) file path.
     */
    abstract protected function get_main_file_path() : string;

    /**
     * Getter for the package root directory.
     *
     * @return string Package root directory.
     */
    public function get_root_dir_path() : string
    {
        return dirname( $this->get_main_file_path() ) . '/';
    }

    /**
     * Getter for the package templates directory.
     *
     * Templates directory contains all the HTML files as well as PHP files containing
     * HTML markup.
     *
     * @return string Package templates directory.
     */
    public function get_templates_dir_path() : string
    {
        return $this->get_root_dir_path() . 'templates/';
    }

    /**
     * Returns URL of the passed dir.
     *
     * @param string $dir_path Path to the dir.
     * @return string
     */
    abstract public function get_dir_url( string $dir_path ) : string;

    /**
     * Adds dashboard error notice.
     *
     * @param string $error_message Notice message.
     * @return void
     */
    public function add_dashboard_error( string $error_message ) : void
    {
        Dashboard::get_instance()->add_error( $this->prepare_dashboard_error_message( $error_message ) );
    }

    /**
     * Customizes error message.
     *
     * @param string $error_message Initial error message.
     * @return string Customized error message.
     */
    abstract public function prepare_dashboard_error_message( string $error_message ) : string;

    public function get_db_data_item( string $name = '', $default_value = null )
    {
        $data = $this->get_db_data_array();

        return isset( $data[ $name ] ) ?
            $data[ $name ] :
            $default_value;
    }

    public function get_db_data_array() : array
    {
        return get_option( $this->get_db_data_option_name(), [] );
    }

    public function update_db_data_item( string $name = '', $value ) : void
    {
        $data = $this->get_db_data_array();

        $data[ $name ] = $value;

        $this->update_db_data_array( $data );
    }

    public function update_db_data_array( array $new_value ) : void
    {
        update_option( $this->get_db_data_option_name(), $new_value );
    }

    protected function get_db_data_option_name() : string
    {
        return $this->get_slug();
    }
}