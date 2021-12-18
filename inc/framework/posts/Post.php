<?php

namespace JMCG\inc\framework\posts;

use \Throwable;
use \Exception;
use \WP_Post;

use \JMCG\inc\framework\posts\fields\acf\ACF_Fields_Manager;
use \JMCG\inc\framework\posts\fields\meta\Meta_Fields_Manager;
use \JMCG\inc\framework\posts\fields\meta\Meta_Field_Descriptor;
use \JMCG\inc\framework\posts\fields\acf\field\Fields;
use \JMCG\inc\framework\posts\fields\acf\field\Field;

if ( ! defined( 'ABSPATH' ) ) exit;

class Post
{
    static protected function get_common_acf_descriptors() : array
    {
        return [];
    }

    static protected function get_common_meta_descriptors() : array
    {
        return [];
    }

    static public function get_by_id( int $post_id ) : Post
    {
        return new static( $post_id );
    }

    protected $id = 0;

    protected $acf_manager = null;

    protected $meta_manager = null;

    protected function __construct( int $post_id )
    {
        $this->id = $post_id;
    }

    public function get_id() : int
    {
        return $this->id;
    }

    public function get_title() : string
    {
        return $this->get_original()->post_title;
    }

    public function get_url()
    {
        return get_permalink( $this->get_id() );
    }

    public function meta() : Meta_Fields_Manager
    {
        if ( empty( $this->meta_manager ) )
        {
            $this->meta_manager = new Meta_Fields_Manager( $this->get_id() );

            $this->meta_manager->register_descriptors( $this->get_meta_descriptors() );
        }

        return $this->meta_manager;
    }

    protected function get_meta_descriptors() : array
    {
        return static::get_common_meta_descriptors();
    }

    public function acf() : ACF_Fields_Manager
    {
        if ( empty( $this->acf_manager ) )
        {
            $this->acf_manager = new ACF_Fields_Manager( $this->get_id() );

            $this->acf_manager->register_descriptors( $this->get_acf_descriptors() );
        }

        return $this->acf_manager;
    }

    protected function get_acf_descriptors() : array
    {
        return static::get_common_acf_descriptors();
    }

    public function get_original() : WP_Post
    {
        return get_post( $this->get_id() );
    }

    public function exists() : bool
    {
        try
        {
            $this->get_original();

            return true;
        }
        catch ( Throwable $error )
        {
            return false;
        }
    }
}