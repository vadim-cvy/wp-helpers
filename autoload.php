<?php
/**
 * Framework version 1.0.0
 */

namespace JMCG;

use \JMCG\inc\framework\package\Package_Autoloader;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/inc/package/Package_Autoloader.php';

new Package_Autoloader( __NAMESPACE__, __DIR__ );