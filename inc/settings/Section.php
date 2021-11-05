<?php

namespace JT\helpers\inc\settings;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A wrapper for add_settings_section().
 */
class Section
{
    /**
     * Section id
     *
     * @var string
     */
    protected $id = '';

    /**
     * Section title.
     *
     * @var string
     */
    protected $title = '';

    /**
     * Section description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Parent settings page.
     *
     * @var Page
     */
    protected $parent_page = null;

    /**
     * @param string $id            See $this->id.
     * @param string $title         See $this->title.
     * @param string $description   See $this->description.
     * @param Page $parent_page     See $this->parent_page.
     */
    public function __construct( string $id, string $title, string $description, Page $parent_page )
    {
        $this->id          = $id;
        $this->title       = $title;
        $this->description = $description;
        $this->parent_page = $parent_page;

        \JT\helpers\inc\WP_Hooks::add_action_ensure( 'admin_init', [ $this, '_register' ] );
    }

    /**
     * Registers the section.
     *
     * @return void
     */
    public function _register()
    {
        add_settings_section(
            $this->get_id(),
            $this->get_title(),
            [ $this, '_print_description' ],
            $this->get_parent_page()->get_slug()
        );
    }

    /**
     * Getter for $this->title.
     *
     * @return string
     */
    public function get_title() : string
    {
        return $this->title;
    }

    /**
     * Getter for $this->id.
     *
     * @return string
     */
    public function get_id() : string
    {
        return $this->id;
    }

    /**
     * Getter for $this->parent_page.
     *
     * @return string
     */
    public function get_parent_page() : Page
    {
        return $this->parent_page;
    }

    /**
     * Getter for $this->title.
     *
     * @return string
     */
    public function _print_description() : void
    {
        echo $this->description;
    }
}