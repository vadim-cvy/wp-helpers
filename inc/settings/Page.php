<?php

namespace JT\helpers\inc\settings;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A wrapper for add_options_page().
 */
abstract class Page
{
    use \JT\helpers\inc\design_pattern\tSingleton;

    /**
     * Page sections.
     *
     * @var array<Section>
     */
    protected $sections = [];

    public function __construct()
    {
        static::set_sections();

        \JT\helpers\inc\WP_Hooks::add_action_ensure( 'admin_menu', [ $this, '_add_to_admin_menu' ] );
    }

    /**
     * Setter for $this->sections.
     *
     * @return void
     */
    protected function set_sections() : void
    {
        $this->sections = $this->generate_sections();
    }

    /**
     * Getter for $this->sections.
     *
     * @return array<Section> Page sections.
     */
    public function get_sections() : array
    {
        return $this->sections;
    }

    /**
     * Sections generator.
     *
     * This method is called once and then the value is cached in $this->sections.
     *
     * @return array<Section>
     */
    abstract protected function generate_sections() : array;

    /**
     * Getter for page title.
     *
     * @return string Page title.
     */
    abstract protected function get_page_title() : string;

    /**
     * Getter for menu title.
     *
     * @return string Menu title.
     */
    abstract protected function get_menu_title() : string;

    /**
     * Getter for page slug.
     *
     * @return string Page slug.
     */
    abstract public function get_slug() : string;

    /**
     * Getter for minimum capability which is required to have access to the page.
     *
     * @return string Capability.
     */
    abstract protected function get_capability() : string;

    /**
     * Adds the page to the admin menu.
     *
     * @return void
     */
    public function _add_to_admin_menu() : void
    {
        add_options_page(
            $this->get_page_title(),
            $this->get_menu_title(),
            $this->get_capability(),
            $this->get_slug(),
            [ $this, '_render' ]
        );
    }

    /**
     * Renders the page.
     *
     * @return void
     */
    public function _render() : void
    {
        $templates_dir_path =
            \JT\helpers\Helpers::get_instance()->get_templates_dir();

        require $templates_dir_path . 'settings/page.php';
    }
}