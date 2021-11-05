<?php

namespace JT\helpers\inc\package;

use \Exception;

use \JT\helpers\inc\WP_Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helps to enqueue package CSS and JS.
 */
class Package_Assets_Manager
{
    /**
     * Instance of the package.
     *
     * @var Package
     */
    protected $package = null;

    /**
     * Names of JS scripts added via $this->enqueue_internal_js().
     *
     * @var array<string>
     */
    protected $internal_js = [];

    /**
     * Names of JS scripts which should be treated as modules.
     *
     * @var array<string>
     */
    protected $type_module_js = [];

    /**
     * @param Package $package See $this->package for documentation.
     */
    public function __construct( Package $package )
    {
        $this->package = $package;

        WP_Hooks::add_filter_ensure( 'script_loader_tag', [ $this, '_maybe_set_type_module' ] );
    }

    /**
     * Sets passed <script> tag "type" attribute to "module" if required.
     *
     * @param   string $script_tag  <script> tag.
     * @param   string $handle      Script name.
     * @return  string              Updated $script_tag.
     */
    public function _maybe_set_type_module( string $script_tag, string $handle ) : string
    {
        if ( in_array( $handle, $this->type_module_js ) )
        {
            $script_tag = str_replace( '<script', '<script type="module"', $script_tag );
        }

        return $script_tag;
    }

    /**
     * Enqueues CSS file which is a part of the package codebase (i.e asset lays in /assets/css/ dir).
     *
     * A wrapper for wp_enqueue_style().
     *
     * @param string $handle        Name of the stylesheet. Should be unique. Will be prefixed.
     * @param string $relative_url  CSS file URL ralative to the package /assets/css/ dir.
     * @param array  $dependencies  An array of registered stylesheet handles this stylesheet
     *                              depends on.
     * @param string $media         The media for which this stylesheet has been defined.
     * @return void
     */
    public function enqueue_internal_css(
        string $handle,
        string $relative_url,
        array $dependencies = [],
        string $media = ''
    ) : void
    {
        $file_path = $this->get_css_dir() . $relative_url;

        $this->validate_file_exists( $file_path );

        $url     = $this->get_css_dir_url() . $relative_url;
        $version = filemtime( $file_path );

        $this->enqueue_css( $handle, $url, $dependencies, $version, $media );
    }

    /**
     * Enqueues CSS file.
     *
     * A wrapper for wp_enqueue_style().
     *
     * @param string $handle        Name of the stylesheet. Should be unique. Will be prefixed.
     * @param string $url           CSS file URL.
     * @param array  $dependencies  An array of registered stylesheet handles this stylesheet
     *                              depends on.
     * @param string $version       File version number.
     * @param string $media         The media for which this stylesheet has been defined.
     * @return void
     */
    public function enqueue_css(
        string $handle,
        string $url,
        array $dependencies = [],
        string $version = '',
        string $media = ''
    ) : void
    {
        $this->validate_can_enqueue_assets();

        $hande = $this->prefix_handle( $handle );

        wp_enqueue_style( $handle, $url, $dependencies, $version, $media );
    }

    /**
     * Enqueues JS file which is a part of the package codebase (i.e asset lays in /assets/js/ dir).
     *
     * A wrapper for wp_enqueue_style().
     *
     * @param string $handle        Name of the script. Should be unique. Will be prefixed.
     * @param string $relative_url  JS file URL ralative to the package /assets/js/ dir.
     * @param array  $dependencies  An array of registered script handles this script
     *                              depends on.
     * @param bool   $in_footer     Whether to enqueue the script before </body> instead of in
     *                              the <head>.
     * @param bool   $is_module     If <script> tag should be type="module"
     * @return void
     */
    public function enqueue_internal_js(
        string $handle,
        string $relative_url,
        array $dependencies = [],
        bool $in_footer = true,
        bool $is_module = false
    ) : void
    {
        $file_path = $this->get_js_dir() . $relative_url;

        $this->validate_file_exists( $file_path );

        $url     = $this->get_js_dir_url() . $relative_url;
        $version = filemtime( $file_path );

        $this->enqueue_js( $handle, $url, $dependencies, $version, $in_footer, $is_module );

        $this->internal_js[] = $handle;
    }

    /**
     * Enqueues JS file which is a part of the package codebase (i.e asset lays in /assets/js/ dir).
     *
     * A wrapper for wp_enqueue_style().
     *
     * @param string $handle        Name of the script. Should be unique. Will be prefixed.
     * @param string $url           JS file URL.
     * @param array  $dependencies  An array of registered script handles this script
     *                              depends on.
     * @param string $version       File version number.
     * @param bool   $in_footer     Whether to enqueue the script before </body> instead of in
     *                              the <head>.
     * @param bool   $is_module     If <script> tag should be type="module"
     * @return void
     */
    public function enqueue_js(
        string $handle,
        string $url,
        array $dependencies = [],
        string $version = '',
        bool $in_footer = true,
        bool $is_module = false
    ) : void
    {
        $this->validate_can_enqueue_assets();

        $handle = $this->prefix_handle( $handle );

        wp_enqueue_script( $handle, $url, $dependencies, $version, $in_footer );

        if ( $is_module )
        {
            $this->type_module_js[] = $handle;
        }
    }

    /**
     * Localizes data for scripts added via $this->enqueue_internal_js().
     *
     * @param   string              $handle Script name.
     * @param   array<mixed,mixed>  $data   Data which should be localized.
     * @return  void
     */
    public function localize_internal_js_data( string $handle, array $data ) : void
    {
        if ( ! in_array( $handle, $this->internal_js ) )
        {
            throw new Exception(
                'Can\'t localize "' . $handle . '" data! ' .
                '"' . $handle . '" is not enqueued yet.'
            );
        }

        $handle      = $this->prefix_handle( $handle );
        $object_name = $this->package->get_slug();

        wp_localize_script( $handle, $object_name, $data );
    }

    /**
     * Prefixes asset handle.
     *
     * @param string $asset_handle Name of the asset. Should be unique.
     * @return string
     */
    protected function prefix_handle( string $asset_handle ) : string
    {
        return $this->package->get_slug() . '_' . $asset_handle;
    }

    /**
     * Validates if $this->enqueue_{js / css}_asset() can be called now.
     *
     * @return void
     */
    protected function validate_can_enqueue_assets() : void
    {
        if ( ! did_action( 'wp_enqueue_scripts' ) && ! did_action( 'admin_enqueue_scripts' ) )
        {
            throw new \Exception(
                '"wp_enqueue_scripts" / "admin_enqueue_scripts" hasn\'t fire yet! ' .
                'Please enqueue your assets the same way but from the ' . static::class . '::enqueue_assets() method.'
            );
        }
    }

    /**
     * Throws error if passed file does not exist.
     *
     * @param string $path File path
     * @return void
     */
    protected function validate_file_exists( string $path ) : void
    {
        if ( ! file_exists( $path ) )
        {
            throw new \Exception( 'Asset file "' . $path . '" does not exist!' );
        }
    }

    /**
     * Returns path to the assets dir.
     *
     * Assets are: CSS, JS, images.
     *
     * @return string
     */
    protected function get_root_dir() : string
    {
        return $this->package->get_root_dir() . 'assets/build/';
    }

    /**
     * Returns path to the assets sub directory.
     *
     * Assets are: CSS, JS, images.
     *
     * @param string $sub_dir_relative_path Path to the sub dir relative to assets dir.
     * @return string
     */
    protected function get_root_sub_dir( string $sub_dir_relative_path ) : string
    {
        return $this->get_root_dir() . trailingslashit( $sub_dir_relative_path );
    }

    /**
     * Returns path to the CSS dir.
     *
     * @return string
     */
    protected function get_css_dir() : string
    {
        return $this->get_root_sub_dir( 'css' );
    }

    /**
     * Retrns CSS dir URL.
     *
     * @return string
     */
    protected function get_css_dir_url() : string
    {
        return $this->package->get_dir_url( $this->get_css_dir() );
    }

    /**
     * Returns path to the JS dir.
     *
     * @return string
     */
    protected function get_js_dir() : string
    {
        return $this->get_root_sub_dir( 'js' );
    }

    /**
     * Retrns JS dir URL.
     *
     * @return string
     */
    protected function get_js_dir_url() : string
    {
        return $this->package->get_dir_url( $this->get_js_dir() );
    }
}