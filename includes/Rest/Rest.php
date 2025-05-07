<?php
namespace DokanKits\Rest;

use DokanKits\Core\Container;

/**
 * REST API Class
 *
 * @package DokanKits\Rest
 */
class Rest {
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
     * Initialize REST API
     *
     * @return void
     */
    public function init() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Register REST API routes
     *
     * @return void
     */
    public function register_routes() {
        try {
            // Initialize settings controller
            $settings_controller = $this->container->get( 'rest.controllers.settings' );
            $settings_controller->register_routes();
        } catch (\Exception $e) {
            // Log error or handle gracefully
        }
        
        /**
         * Action after REST API routes are registered
         *
         * @param Rest $this Rest instance
         */
        do_action( 'dokan_kits_rest_routes_registered', $this );
    }
}