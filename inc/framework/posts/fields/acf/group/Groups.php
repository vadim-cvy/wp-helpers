<?php

namespace JMCG\inc\framework\posts\fields\acf\group;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Incapsulates ACF groups common helpers.
 */
class Groups
{
    /**
     * Returns all registered groups.
     *
     * @return array<Group>
     */
    public static function get_all() : array
    {
        return static::get_by_filters([]);
    }

    /**
     * Returns group by specified group id.
     *
     * @param   integer $group_id   Group id.
     * @return  Group               Group.
     */
    public static function get_by_id( int $group_id ) : Group
    {
        $group_key = static::get_key_by_id( $group_id );

        return new Group( $group_key );
    }

    /**
     * Returns all groups that belong to specified post type.
     *
     * @param   string $post_type   Post type name.
     * @return  array<Group>        Groups that belong to the post type.
     */
    public static function get_by_post_type( string $post_type ) : array
    {
        return static::get_by_filters([
            'post_type' => $post_type,
        ]);
    }

    /**
     * Returns all groups that belong to specified taxonomy.
     *
     * @param   string $post_type   Taxonomy name.
     * @return  array<Group>        Groups that belong to the taxonomy.
     */
    public static function get_by_taxonomy( string $taxonomy ) : array
    {
        return static::get_by_filters([
            'taxonomy' => $taxonomy,
        ]);
    }

    /**
     * RA wrapper for acf_get_field_groups().
     *
     * @param   array<string,mixed> $filters    Filters the groups should be filtered by.
     * @return  array<Group>                    Groups that belong to the post type.
     */
    public static function get_by_filters( array $filters ) : array
    {
        $groups = [];

        foreach ( acf_get_field_groups( $filters ) as $group )
        {
            $groups[] = new Group( $group['key'] );
        }

        return $groups;
    }

    /**
     * Returns group key by specified group id.
     *
     * @param   string $group_id    Group id (post id).
     * @return  string              Group key.
     */
    public static function get_key_by_id( string $group_id ) : string
    {
        $group_post = get_post( $group_id );

        if ( empty( $group_post ) )
        {
            return '';
        }

        return $group_post->post_name;
    }
}