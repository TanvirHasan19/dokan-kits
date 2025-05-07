<?php
namespace DokanKits\Core;

/**
 * Assets Class
 *
 * @package DokanKits\Core
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
     * Setup assets
     *
     * @return void
     */
    public function setup() {
        // Admin assets
        add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );
        
        // Frontend assets
        add_action( 'wp_enqueue_scripts', [ $this, 'register_frontend_assets' ] );
        
        /**
         * Action after assets setup
         *
         * @param Assets $this Assets instance
         */
        do_action( 'dokan_kits_assets_setup', $this );
    }

    /**
     * Register admin assets
     *
     * @param string $hook Current admin page hook
     *
     * @return void
     */
    public function register_admin_assets( $hook ) {
        // Only load on specific plugin pages
        if ( strpos( $hook, 'dokan-kits' ) === false ) {
            return;
        }

        // Register Font Awesome
        wp_enqueue_style( 
            'dokan-kits-font-awesome', 
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css', 
            [], 
            '6.6.0' 
        );

        // Register admin styles
        wp_enqueue_style( 
            'dokan-kits-admin', 
            DOKAN_KITS_ASSETS_URL . '/css/admin.css', 
            [], 
            DOKAN_KITS_VERSION 
        );

        // Register admin scripts
        wp_enqueue_script( 
            'dokan-kits-admin', 
            DOKAN_KITS_ASSETS_URL . '/js/admin.js', 
            [ 'jquery' ], 
            DOKAN_KITS_VERSION, 
            true 
        );

        // Add localization data for the admin script
        wp_localize_script( 
            'dokan-kits-admin', 
            'dokanKitsAdmin', 
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'dokan_kits_admin_nonce' ),
                'i18n'    => [
                    'changes_saved' => __( 'Changes saved successfully!', 'dokan-kits' ),
                    'error'         => __( 'An error occurred. Please try again.', 'dokan-kits' ),
                ]
            ] 
        );

        /**
         * Action after registering admin assets
         *
         * @param string $hook   Current admin page hook
         * @param Assets $this   Assets instance
         */
        do_action( 'dokan_kits_admin_assets_registered', $hook, $this );
    }

    /**
     * Register frontend assets
     *
     * @return void
     */
    public function register_frontend_assets() {
        // Don't load if not needed
        if ( ! $this->should_load_frontend_assets() ) {
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

        // Add localization data for the frontend script
        wp_localize_script( 
            'dokan-kits-frontend', 
            'dokanKitsFrontend', 
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'dokan_kits_frontend_nonce' ),
            ] 
        );

        /**
         * Action after registering frontend assets
         *
         * @param Assets $this Assets instance
         */
        do_action( 'dokan_kits_frontend_assets_registered', $this );
    }

    /**
     * Check if frontend assets should be loaded
     *
     * @return bool
     */
    protected function should_load_frontend_assets() {
        $should_load = false;
        
        // Check if any feature that requires frontend assets is enabled
        
        // Image validation
        if ( get_option( 'enable_dimension_restrictions' ) || get_option( 'enable_size_restrictions' ) ) {
            $should_load = true;
        }
        
        // Cart button hiding
        if ( get_option( 'hide_add_to_cart_button_checkbox' ) ) {
            $should_load = true;
        }
        
        /**
         * Filter whether to load frontend assets
         *
         * @param bool   $should_load Whether to load frontend assets
         * @param Assets $this        Assets instance
         */
        return apply_filters( 'dokan_kits_load_frontend_assets', $should_load, $this );
    }
}