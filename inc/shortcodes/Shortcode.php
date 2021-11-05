<?php

namespace JT\helpers\inc\shortcodes;

use \JT\helpers\inc\WP_Hooks;
use \JT\helpers\inc\design_pattern\tSingleton;

abstract class Shortcode
{
    use tSingleton;

    protected function __construct()
    {
        WP_Hooks::add_action_ensure( 'init', [ $this, '_register' ] );
    }

    public function _register() : void
    {
        add_shortcode( $this->get_slug(), [ $this, '_get_content' ] );
    }

    abstract public function get_slug() : string;

    public function _get_content( $attributes, string $content ) : string
    {
        if ( empty( $attributes ) )
        {
            $attributes = [];
        }

        ob_start();

        $this->render( $attributes, $content );

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    abstract protected function render( array $attributes, string $content ) : void;
}