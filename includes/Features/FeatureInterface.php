<?php
namespace DokanKits\Features;

/**
 * Feature Interface
 *
 * @package DokanKits\Features
 */
interface FeatureInterface {
    /**
     * Initialize the feature
     *
     * @return void
     */
    public function init();

    /**
     * Check if the feature is enabled
     *
     * @return bool
     */
    public function is_enabled();

    /**
     * Get feature name
     *
     * @return string
     */
    public function get_name();

    /**
     * Get feature description
     *
     * @return string
     */
    public function get_description();
}