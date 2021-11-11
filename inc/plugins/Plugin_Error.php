<?php

namespace Cvy\helpers\inc\plugins;

use \Exception;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Exception which is thrown by the plugins related classes.
 *
 * Is used to make try-catch more clear.
 */
class Plugin_Error extends Exception {}