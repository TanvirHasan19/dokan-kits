<?php
namespace DokanKits\Frontend;

use DokanKits\Core\Container;

/**
 * Frontend Class
 *
 * @package DokanKits\Frontend
 */
class Frontend {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Initialize frontend
     *
     * @return void
     */
    public function init() {
        // Initialize frontend assets
        try {
            $assets = $this->container->get( 'frontend.assets' );
            $assets->init();
        } catch (\Exception $e) {
            // Log error or handle gracefully
        }

        /**
         * Action after frontend initialization
         *
         * @param Frontend $this Frontend instance
         */
        do_action( 'dokan_kits_frontend_init', $this );
    }
}