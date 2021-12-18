<?php

namespace JMCG\inc\framework\taxonomies;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Implements common methods to make it easier to work with taxonomies.
 */
class Taxonomies
{
    public static function get_visible() : array
    {
        return get_taxonomies([
            'public'  => true,
            'show_ui' => true,
        ], 'objects' );
    }
}
