<?php

namespace JMCG\inc\framework\pluggable;

use \JMCG\inc\framework\exceptions\Custom_Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Exception which is thrown by the pluggable (plugins/themes) classes.
 *
 * Is used to make try-catch more clear.
 */
class Pluggable_Error extends Custom_Exception
{
    static function get_error_codes_map() : array
    {
        return [
            'not_installed' => 1,
            'not_active' => 2,
        ];
    }
}