<?php
namespace DokanKits\Admin;

use DokanKits\Core\Container;

/**
 * Admin Class
 *
 * @package DokanKits\Admin
 */
class Admin {
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
     * Initialize admin
     *
     * @return void
     */
    public function init() {
        // Initialize components
        $this->init_menu();
        $this->init_assets();
        $this->init_notices();
        $this->init_settings();

        /**
         * Action after admin initialization
         *
         * @param Admin $this Admin instance
         */
        do_action( 'dokan_kits_admin_init', $this );
    }

    /**
     * Initialize admin menu
     *
     * @return void
     */
    protected function init_menu() {
        try {
            $menu = $this->container->get( 'admin.menu' );
            $menu->init();
        } catch (\Exception $e) {
            // Log error or handle gracefully
            error_log('Error initializing admin menu: ' . $e->getMessage());
            
            // Fallback - register menu directly
            add_action('admin_menu', function() {
                add_menu_page(
                    __('Dokan Kits', 'dokan-kits'),
                    __('Dokan Kits', 'dokan-kits'),
                    'manage_options',
                    'dokan-kits',
                    function() { 
                        echo '<div class="wrap"><h1>Dokan Kits</h1><p>Settings page is loading...</p></div>'; 
                    },
                    'dashicons-editor-unlink',
                    56
                );
            });
        }
    }

    /**
     * Initialize admin assets
     *
     * @return void
     */
    protected function init_assets() {
        try {
            $assets = $this->container->get( 'admin.assets' );
            $assets->init();
        } catch (\Exception $e) {
            // Log error or handle gracefully
            error_log('Error initializing admin assets: ' . $e->getMessage());
            
            // Fallback - register assets directly
            add_action('admin_enqueue_scripts', function($hook) {
                if ('toplevel_page_dokan-kits' !== $hook) {
                    return;
                }
                
                wp_enqueue_style(
                    'dokan-kits-admin',
                    DOKAN_KITS_ASSETS_URL . '/css/admin.css',
                    [],
                    DOKAN_KITS_VERSION
                );
                
                wp_enqueue_script(
                    'dokan-kits-admin',
                    DOKAN_KITS_ASSETS_URL . '/js/admin.js',
                    ['jquery'],
                    DOKAN_KITS_VERSION,
                    true
                );
            });
        }
    }

    /**
     * Initialize admin notices
     *
     * @return void
     */
    protected function init_notices() {
        try {
            $notices = $this->container->get( 'admin.notices' );
            $notices->init();
        } catch (\Exception $e) {
            // Log error or handle gracefully
            error_log('Error initializing admin notices: ' . $e->getMessage());
            
            // No fallback needed for notices
        }
    }

    /**
     * Initialize settings
     *
     * @return void
     */
    protected function init_settings() {
        try {
            $settings = $this->container->get( 'admin.settings' );
            $settings->init();
        } catch (\Exception $e) {
            // Log error or handle gracefully
            error_log('Error initializing admin settings: ' . $e->getMessage());
            
            // Fallback - register settings directly
            add_action('admin_init', function() {
                register_setting('dokan_kits_settings_group', 'dokan_kits_settings');
                
                // Register legacy settings
                $legacy_settings = [
                    'remove_vendor_checkbox',
                    'set_default_seller_role_checkbox',
                    'remove_become_a_vendor_button_checkbox',
                    'enable_own_product_purchase_checkbox',
                    'remove_variable_product_checkbox',
                    'remove_external_product_checkbox',
                    'remove_grouped_product_checkbox',
                    'remove_split_shipping_checkbox',
                    'remove_split_shipping_pro_checkbox',
                    'hide_add_to_cart_button_checkbox',
                    'auto_complete_order_checkbox',
                    // Add other legacy settings as needed
                ];
                
                foreach ($legacy_settings as $setting) {
                    register_setting('dokan_kits_settings_group', $setting);
                }
            });
        }
    }
}