<?php
namespace DokanKits\Frontend;

use DokanKits\Core\Container;

/**
 * Frontend Assets Class
 *
 * @package DokanKits\Frontend
 */
class Assets {
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
     * Initialize assets
     *
     * @return void
     */
    public function init() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * Register frontend assets
     *
     * @return void
     */
    public function register_assets() {
        // Only enqueue if needed
        if ( ! $this->should_load_assets() ) {
            return;
        }

        // Register styles
        wp_enqueue_style( 
            'dokan-kits-frontend', 
            DOKAN_KITS_ASSETS_URL . '/css/frontend.css', 
            [], 
            DOKAN_KITS_VERSION 
        );

        // Register scripts
        wp_enqueue_script( 
            'dokan-kits-frontend', 
            DOKAN_KITS_ASSETS_URL . '/js/frontend.js', 
            [ 'jquery' ], 
            DOKAN_KITS_VERSION, 
            true 
        );

        // Add localization data
        wp_localize_script( 
            'dokan-kits-frontend', 
            'dokanKitsFrontend', 
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'dokan_kits_frontend_nonce' ),
            ] 
        );

        /**
         * Action after frontend assets are registered
         *
         * @param Assets $this Assets instance
         */
        do_action( 'dokan_kits_frontend_assets_registered', $this );
    }

    /**
     * Check if assets should be loaded
     *
     * @return bool
     */
    protected function should_load_assets() {
        $should_load = false;
        
        // Load if any of these features are enabled
        $features_requiring_assets = [
            'enable_dimension_restrictions',
            'enable_size_restrictions',
            'hide_add_to_cart_button_checkbox',
        ];
        
        foreach ( $features_requiring_assets as $feature ) {
            if ( get_option( $feature ) === '1' ) {
                $should_load = true;
                break;
            }
        }
        
        // Load on vendor dashboard
        if ( function_exists( 'dokan_is_seller_dashboard' ) && dokan_is_seller_dashboard() ) {
            $should_load = true;
        }
        
        /**
         * Filter whether to load frontend assets
         *
         * @param bool   $should_load Whether to load assets
         * @param Assets $this        Assets instance
         */
        return apply_filters( 'dokan_kits_load_frontend_assets', $should_load, $this );
    }
}