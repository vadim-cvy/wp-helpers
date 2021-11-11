<?php
/**
 * Helpers version: 1.1.2
 */

namespace Cvy\helpers;

use \Exception;

use \Cvy\helpers\inc\package\Package_Autoloader;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check if current file lays under the plugins dir.
 *
 * @return boolean
 */
function cvy_is_plugins_dir() : bool
{
    return basename( realpath( __DIR__ . '/../..' ) ) === 'plugins';
}

/**
 * Check if current file lays under the themes dir.
 *
 * @return boolean
 */
function cvy_is_themes_dir() : bool
{
    return basename( realpath( __DIR__ . '/../..' ) ) === 'themes';
}

if ( ! cvy_is_plugins_dir() && ! cvy_is_themes_dir() )
{
    throw new Exception(
        'Seems like you have placed helpers dir into wrong location. ' .
        '"' . __DIR__ . '" directory should be placed into your package root dir. ' .
        'If your package is a theme than helpers dir path must be ".../wp-content/themes/{your theme name}/helpers/".' .
        'If your package is a plugin than helpers dir path must be ".../wp-content/plugins/{your plugin name}/helpers/".'
    );
}

require_once __DIR__ . '/inc/package/Package_Autoloader.php';

$package_root_dir = __DIR__ . '/../';

new Package_Autoloader( 'cvy', $package_root_dir  );

Helpers::get_instance();