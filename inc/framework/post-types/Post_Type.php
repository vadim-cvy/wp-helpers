<?php

namespace JMCG\inc\framework\post_types;

use \WP_Query;
use \WP_Post;

use \JMCG\inc\framework\posts\Post;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Post_Type
{
    protected static function wrap_post( WP_Post $post ) : Post
    {
        return Post::get_by_id( $post->ID );
    }

    public static function get_posts( array $args = [] ) : array
    {
        $posts = static::query( $args )->posts;

        if ( empty( $args['fields'] ) )
        {
            foreach ( $posts as $i => $post )
            {
                $posts[ $i ] = static::wrap_post( $post );
            }
        }

        return $posts;
    }

    public static function query( array $args = [] ) : WP_Query
    {
        $default_args = [
            'post_type'   => static::get_slug(),
            'post_status' => 'publish',
        ];

        $args = array_merge( $default_args, $args );

        return new WP_Query( $args );
    }

    abstract public static function get_slug() : string;
}