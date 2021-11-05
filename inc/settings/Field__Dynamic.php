<?php

namespace JT\helpers\inc\settings;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Setting field.
 *
 * A wrapper for add_settings_field().
 *
 * May have multiple instances.
 */
abstract class Field__Dynamic extends Field__Singleton
{
    public function __construct()
    {
        parent::__construct();
    }
}