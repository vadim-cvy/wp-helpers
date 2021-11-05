<?php

namespace Cvy\helpers\inc\dashboard\tables;

use \Cvy\helpers\inc\WP_Hooks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of the WP dashboard table column.
 *
 * Works for all types of tables: post types, taxonomies, users.
 */
abstract class Column
{
    /**
     * Name of the screen the column belongs to.
     *
     * @var string  Screen name. Ex:
     *              "my_custom_post_type" - My Custom Post Type posts table;
     *              "my_custom_taxonomy" - My Custom Taxonomy terms table;
     *              "users" - Users table.
     */
    protected $screen_name = '';

    /**
     * @param string $screen_name See documentation of $this->screen_name.
     */
    protected function __construct( string $screen_name )
    {
        $this->screen_name = $screen_name;

        $this->register();
    }

    /**
     * Registers the column in the dashboard table.
     *
     * @return void
     */
    protected function register() : void
    {
        $is_users_screen = $this->screen_name === 'users';

        if ( $is_users_screen )
        {
            $add_column_hook_name = 'manage_' . $this->screen_name . '_columns';
        }
        else
        {
            $add_column_hook_name = 'manage_edit-' . $this->screen_name . '_columns';
        }

        $is_post_type_screen = ! empty( get_post_type_object( $this->screen_name ) );

        if ( $is_post_type_screen )
        {
            $print_cell_hook_name = 'manage_' . $this->screen_name . '_posts_custom_column';
        }
        else
        {
            $print_cell_hook_name = 'manage_' . $this->screen_name . '_custom_column';
        }

        WP_Hooks::add_filter_ensure( $add_column_hook_name, [ $this, '_add_column' ] );

        WP_Hooks::add_action_ensure( $print_cell_hook_name, [ $this, '_print_column_cell' ] );
    }

    /**
     * A callback for 'manage_{screen_name}_columns'.
     *
     * @param   array<string> $table_columns    Inital table columns.
     * @return  array<string>                   $table_columns merged with current column.
     */
    public function _add_column( array $table_columns ) : array
    {
        $table_columns[ $this->get_name() ] = $this->get_title();

        return $table_columns;
    }

    /**
     * Prints column cell.
     *
     * @param string        $arg_1  Column name in case current table is post type table,
     *                              empty string otherwise.
     * @param string|int    $arg_2  Empty string in case current table is post type table,
     *                              column name otherwise;
     * @param integer       $arg_3  Empty string in case current table is post type table,
     *                              Term id in case current table is taxonomy table,
     *                              User id in case current table is users table.
     * @return string               Cell content in case current table is users table,
     *                              empty string otherwise.
     */
    public function _print_column_cell( string $arg_1, $arg_2, int $arg_3 = 0 ) : string
    {
        // WP has a strange sense of humor and it passes a blank string as a first argument
        // while second argument is column name (for taxonomies and users). But post types
        // recieve column name as a first argument...
        $column_name =
            $this->get_current_table_type() === 'posts' ?
            $arg_1 :
            $arg_2;

        if ( $column_name !== $this->get_name() )
        {
            return $this->get_current_table_type() === 'users' ? $arg_1 : '';
        }

        $object_id =
            ! empty( $arg_1 ) ?
            $arg_2 :
            $arg_3;

        ob_start();

        $this->create_cell( $object_id )->print();

        $content = ob_get_contents();

        ob_end_clean();

        // Users table don't want we to print the cell as we do for post and tax tables.
        // Users table wants we to return a content as a string.
        // Funny.
        if ( $this->get_current_table_type() !== 'users' )
        {
            echo $content;
        }

        return $content;
    }

    /**
     * Creates a table cell instance based on the passed object id.
     *
     * Use $this->get_current_table_type() to detect if current table is posts table,
     * or taxonomies, or users.
     *
     * @param integer $object_id    Post id, or term id, or user id.
     * @return iCell                Cell instance.
     */
    abstract protected function create_cell( int $object_id ) : iCell;

    protected function get_current_table_type() : string
    {
        $screen = get_current_screen();

        if ( $screen->base === 'edit' )
        {
            return 'posts';
        }
        else if ( $screen->base === 'edit-tags' )
        {
            return 'tax';
        }
        else if ( $screen->base === 'users' )
        {
            return 'users';
        }
    }

    /**
     * Returns column name (slug).
     *
     * @return string Column name (slug).
     */
    abstract protected function get_name() : string;

    /**
     * Returns column title.
     *
     * @return string Column title.
     */
    abstract protected function get_title() : string;
}
