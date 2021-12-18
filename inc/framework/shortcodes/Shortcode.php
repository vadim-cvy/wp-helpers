<?php

namespace JMCG\inc\framework\shortcodes;

use \Exception;

use \JMCG\inc\framework\hooks\Hooks;
use \JMCG\inc\framework\design_pattern\tSingleton;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Shortcode
{
    use tSingleton;

    protected function __construct()
    {
        Hooks::get_action( 'init' )->add_callback( [ $this, '_register' ] );
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

    /**
     * Checks if current post contains this shortcode.
     *
     * @return boolean True if curent post contains this shortcode, false otherwise.
     */
    public function appears_in_current_post() : bool
    {
        if ( ! is_singular() )
        {
            return false;
        }

        return $this->appears_in_post( get_the_ID() );
    }

    /**
     * Checks if post contains this shortcode.
     *
     * @return boolean True if post contains this shortcode, false otherwise.
     */
    public function appears_in_post( int $post_id ) : bool
    {
        $post_content = get_post_field( 'post_content', $post_id );

        return has_shortcode( $post_content, $this->get_slug() );
    }
}