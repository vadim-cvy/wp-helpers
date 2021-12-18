<?php

namespace JMCG\inc\framework\taxonomies;

use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Taxonomy
{
    abstract public static function get_slug() : string;

    public static function get_terms( array $args = [] ) : array
    {
        $args['taxonomy'] = static::get_slug();

        $terms = get_terms( $args );

        if ( is_wp_error( $terms ) )
        {
            throw new Exception( $terms->get_error_message() );
        }

        return $terms;
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
}