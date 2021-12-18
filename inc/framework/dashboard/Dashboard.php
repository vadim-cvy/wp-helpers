<?php

namespace JMCG\inc\framework\dashboard;

use \JMCG\inc\framework\hooks\Hooks;

use \JMCG\inc\framework\package\filesystem\Package_Filesystem;

if ( ! defined( 'ABSPATH' ) ) exit;

class Dashboard
{
    /**
     * Adds error dashboard notice.
     *
     * @param string $message Error message.
     * @return void
     */
    static public function add_error( string $message ) : void
    {
        $this->add_notice( 'error', $message );
    }

    /**
     * Adds dashboard notice.
     *
     * @param string $type      Notice type (error, info, etc).
     * @param string $message   Notice message.
     * @return void
     */
    static protected function add_notice( string $type, string $message ) : void
    {
        Hooks::get_action( 'admin_notices' )->add_callback(function() use ( $type, $message ) : void
        {
            Package_Filesystem::get_templates_dir()
                ->get_file( 'dashboard-notices.php' )
                ->require( $type, $message );
        });
    }
}