<?php

namespace JMCG\inc\framework\exceptions;

use \Throwable;
use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Custom_Exception extends Exception
{
    public static function is_code( string $human_readable_error_code, $error ) : bool
    {
        return $error->getCode() === static::get_error_code( $human_readable_error_code );
    }

    /**
     * Returns array of human readable error codes mapped with their integer values.
     *
     * @return array<string,int> {human readable error code} => {integer error code}.
     */
    abstract static function get_error_codes_map() : array;

    /**
     * Returns integer error code.
     *
     * @param string $human_readable_error_code Human readable error code.
     * @return int Integer error code.
     */
    static function get_error_code( string $human_readable_error_code ) : int
    {
        $codes_map = static::get_error_codes_map();

        if ( ! in_array( $human_readable_error_code, $codes_map ) )
        {
            throw new Exception(
                'Error code is not registered: "' . $human_readable_error_code . '"!'
            );
        }

        return $codes_map[ $human_readable_error_code ];
    }

    public function __construct( string $message, string $human_readable_code, Throwable $previous = null )
    {
        $int_code = static::get_error_code( $human_readable_error_code );

        parent::__construct( $message, $int_code, $previous );
    }
}