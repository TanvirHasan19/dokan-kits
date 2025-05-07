<?php
namespace DokanKits\Admin\Settings\Tabs;

/**
 * Tab Interface
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
interface TabInterface {
    /**
     * Get tab ID
     *
     * @return string
     */
    public function get_id();

    /**
     * Get tab title
     *
     * @return string
     */
    public function get_title();

    /**
     * Get tab icon
     *
     * @return string
     */
    public function get_icon();

    /**
     * Get tab settings
     *
     * @return array
     */
    public function get_settings();

    /**
     * Render tab content
     *
     * @return void
     */
    public function render();
}