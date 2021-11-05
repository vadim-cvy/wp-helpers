<?php

namespace JT\helpers\inc\post_types;

use \Exception;
use \WP_Query;

use \JT\helpers\inc\WP_Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Custom_Post_Type
{
    public static function query_posts( array $args = [] ) : array
    {
        return static::query( $args )->posts;
    }

    public static function query( array $args = [] ) : WP_Query
    {
        $default_args = [
            'post_type' => static::get_slug(),
        ];

        $args = array_merge( $default_args, $args );

        return new WP_Query( $args );
    }

    public static function register() : void
    {
        WP_Hooks::add_action_ensure( 'init', [ get_called_class(), '_register' ] );
    }

    public static function _register() : void
    {
        register_post_type( static::get_slug(), static::get_data() );
    }

    abstract public static function get_slug() : string;

    protected static function get_data() : array
    {
        $singular_name = static::get_singular_name();
        $plural_name   = static::get_plural_name();

        $labels = [
            'name'                  => $plural_name,
            'singular_name'         => $singular_name,
            'menu_name'             => $plural_name,
            'name_admin_bar'        => $singular_name,
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New ' . $singular_name,
            'new_item'              => 'New ' . $singular_name,
            'edit_item'             => 'Edit ' . $singular_name,
            'view_item'             => 'View ' . $singular_name,
            'all_items'             => 'All ' . $plural_name,
            'search_items'          => 'Search ' . $plural_name,
            'parent_item_colon'     => 'Parent ' . $plural_name . ':',
            'not_found'             => 'No ' . $plural_name . ' found.',
            'not_found_in_trash'    => 'No ' . $plural_name . ' found in Trash.',
            'featured_image'        => $singular_name . ' Cover Image',
            'set_featured_image'    => 'Set cover image',
            'remove_featured_image' => 'Remove cover image',
            'use_featured_image'    => 'Use as cover image',
            'archives'              => $singular_name . ' archives',
            'insert_into_item'      => 'Insert into ' . $singular_name,
            'uploaded_to_this_item' => 'Uploaded to this ' . $singular_name,
            'filter_items_list'     => 'Filter ' . $plural_name . ' list',
            'items_list_navigation' => $plural_name . ' list navigation',
            'items_list'            => $plural_name . ' list',
        ];

        return [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => static::get_supported_fields(),
        ];
    }

    abstract protected static function get_singular_name() : string;

    abstract protected static function get_plural_name() : string;

    protected static function get_supported_fields() : array
    {
        return [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ];
    }
}