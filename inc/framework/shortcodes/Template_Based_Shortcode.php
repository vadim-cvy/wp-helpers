<?php

namespace JMCG\inc\framework\shortcodes;

use \JMCG\inc\framework\hooks\Hooks;
use \JMCG\inc\framework\template_controllers\Template_Controller;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Template_Based_Shortcode extends Shortcode
{
    protected function __construct()
    {
        parent::__construct();

        Hooks::get_action( 'wp' )->add_callback( [ $this, '_maybe_init_template_controller' ] );
    }

    public function _maybe_init_template_controller() : void
    {
        if ( $this->appears_in_current_post() )
        {
            $this->get_template_controller_instance();
        }
    }

    protected function render( array $attributes, string $content ) : void
    {
        $this->get_template_controller_instance()->render();
    }

    abstract protected function get_template_controller_instance() : Template_Controller;
}