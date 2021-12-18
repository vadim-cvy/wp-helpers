<?php

namespace JMCG\inc\framework\arrays;

if ( ! defined( 'ABSPATH' ) ) exit;

class Arrays
{
    public static function flat( array $multidimensional_array ) : array
    {
        $flat_array = [];

        array_walk_recursive( $multidimensional_array, function( $value, $key ) use ( &$flat_array )
        {
            $flat_array[ $key ] = $value;
        });

        return $flat_array;
    }
}