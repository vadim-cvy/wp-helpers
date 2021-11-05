<?php

namespace Cvy\helpers\inc\dashboard;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Representation of WP Dashboard.
 */
class Dashboard
{
    use \Cvy\helpers\inc\design_pattern\tSingleton;

    /**
     * Dashboard notices which were added via this class.
     *
     * @var array<string,array<string>> Notices.
     */
    protected $notices = [
        'error' => []
    ];

    protected function __construct()
    {
        \Cvy\helpers\inc\WP_Hooks::add_action_ensure( 'admin_notices', [ $this, '_print_notices' ] );
    }

    /**
     * Adds error dashboard notice.
     *
     * @param string $message Error message.
     * @return void
     */
    public function add_error( string $message ) : void
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
    protected function add_notice( string $type, string $message ) : void
    {
        if ( did_action( 'admin_notices' ) )
        {
            throw new \Exception(
                'Can\'t add a new dashboard notice! Notices have already been printed.'
            );
        }

        $this->notices[ $type ][] = $message;
    }

    /**
     * Prints notices added via this class.
     *
     * @return void
     */
    public function _print_notices() : void
    {
        $templates_dir_path =
            \Cvy\helpers\Helpers::get_instance()->get_templates_dir();

        require_once $templates_dir_path . 'dashboard-notices.php';
    }
}