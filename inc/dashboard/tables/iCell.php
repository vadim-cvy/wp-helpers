<?php

namespace Cvy\helpers\inc\dashboard\tables;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of the WP Dashboard column cell.
 */
interface iCell
{
    public function print() : void;
}
