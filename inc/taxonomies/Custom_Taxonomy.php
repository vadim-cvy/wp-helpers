<?php

namespace Cvy\helpers\inc\taxonomies;

use \Exception;

use \Cvy\helpers\inc\WP_Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Custom_Taxonomy
{
    public static function get_terms( array $args = [] ) : array
    {
        $args = array_merge([
            'taxonomy' => static::get_slug(),
        ], $args );

        return get_terms( $args );
    }

    public static function create_term( string $label ) : int
    {
        $result = wp_create_term( $label, static::get_slug() );

        if ( is_wp_error( $result ) )
        {
            throw new Exception( $result->get_error_message() );
        }

        return is_array( $result ) ? $result['term_id'] : $result;
    }

    public static function register() : void
    {
        WP_Hooks::add_action_ensure( 'init', [ get_called_class(), '_register' ] );
    }

    public static function _register() : void
    {
        register_taxonomy( static::get_slug(), static::get_post_types(), static::get_data() );
    }

    abstract public static function get_slug() : string;

    protected static function get_data() : array
    {
        $singular_name = static::get_singular_name();

        $plural_name = static::get_plural_name();

        $labels = [
            'name'              => $plural_name,
            'singular_name'     => $singular_name,
            'search_items'      => 'Search ' . $plural_name,
            'all_items'         => 'All ' . $plural_name,
            'parent_item'       => 'Parent ' . $singular_name,
            'parent_item_colon' => 'Parent ' . $singular_name . ':',
            'edit_item'         => 'Edit ' . $singular_name,
            'update_item'       => 'Update ' . $singular_name,
            'add_new_item'      => 'Add New ' . $singular_name,
            'new_item_name'     => 'New ' . $singular_name . ' Name',
            'menu_name'         => $singular_name,
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => true,
        ];

        if ( ! static::has_metabox() )
        {
            $args['show_in_quick_edit'] = false;
            $args['meta_box_cb']        = false;
        }

        return $args;
    }

    abstract protected static function get_post_types() : array;

    abstract protected static function get_singular_name() : string;

    abstract protected static function get_plural_name() : string;

    protected static function has_metabox() : bool
    {
        return true;
    }
}