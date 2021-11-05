<?php

namespace JT\helpers\inc\acf\group;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of the ACF group.
 */
class Group
{
    /**
     * Group key.
     *
     * @var string
     */
    protected $key = '';

    /**
     * @param string $group_key Group key.
     */
    public function __construct( string $group_key )
    {
        $this->key = $group_key;
    }

    /**
     * Group key getter.
     *
     * @return string Group key.
     */
    public function get_key() : string
    {
        return $this->key;
    }

    /**
     * Wrapper for acf_get_field_group().
     *
     * @return array<string,mixed> Group object.
     */
    public function get_original() : array
    {
        return acf_get_field_group( $this->get_key() );
    }

    /**
     * Returns group label(title).
     *
     * @return string Group label(title).
     */
    public function get_label() : string
    {
        return $this->get_original()['title'];
    }
}