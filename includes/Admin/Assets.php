<?php
namespace DokanKits\Admin;

use DokanKits\Core\Container;

/**
 * Admin Assets Class
 *
 * @package DokanKits\Admin
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
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * Register admin assets
     *
     * @param string $hook Current admin page hook
     *
     * @return void
     */
    public function register_assets( $hook ) {
        // Only load on plugin's settings page
        if ( 'toplevel_page_dokan-kits' !== $hook ) {
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
         * Action after admin scripts and styles are registered
         *
         * @param string $hook Current hook
         * @param Assets $this Assets instance
         */
        do_action( 'dokan_kits_admin_scripts_registered', $hook, $this );
    }

    /**
     * Enqueue settings page assets
     *
     * @return void
     */
    public function enqueue_settings_assets() {
        // These assets are already registered in register_assets()
        // This method is called by the Menu class when loading the settings page
        // It's a hook for future extensions to add additional assets
        
        /**
         * Action for enqueuing additional settings assets
         *
         * @param Assets $this Assets instance
         */
        do_action( 'dokan_kits_enqueue_settings_assets', $this );
    }
}