<?php
namespace DokanKits\Features;

use DokanKits\Core\Container;

/**
 * Abstract Feature
 *
 * @package DokanKits\Features
 */
abstract class AbstractFeature implements FeatureInterface {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Feature name
     *
     * @var string
     */
    protected $name;

    /**
     * Feature description
     *
     * @var string
     */
    protected $description;

    /**
     * Feature option key
     *
     * @var string
     */
    protected $option_key;

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Initialize the feature
     *
     * This method should be implemented by child classes
     *
     * @return void
     */
    public function init() {
        if ( $this->is_enabled() ) {
            $this->setup();
            
            /**
             * Fire action when feature is initialized
             *
             * @param AbstractFeature $this Feature instance
             */
            do_action( 'dokan_kits_feature_' . $this->get_slug() . '_init', $this );
        }
    }

    /**
     * Setup the feature
     *
     * This method should be implemented by child classes
     *
     * @return void
     */
    abstract protected function setup();

    /**
     * Check if the feature is enabled
     *
     * @return bool
     */
    public function is_enabled() {
        if ( ! $this->option_key ) {
            return true;
        }

        $enabled = get_option( $this->option_key, false ) === '1';

        /**
         * Filter whether a feature is enabled
         *
         * @param bool           $enabled Whether the feature is enabled
         * @param AbstractFeature $this    Feature instance
         */
        return apply_filters( 'dokan_kits_feature_' . $this->get_slug() . '_enabled', $enabled, $this );
    }

    /**
     * Get feature name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get feature description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Get feature slug
     *
     * @return string
     */
    public function get_slug() {
        $class_name = get_class( $this );
        $parts = explode( '\\', $class_name );
        $last_part = end( $parts );

        return strtolower( preg_replace( '/(?<!^)[A-Z]/', '_$0', $last_part ) );
    }
}