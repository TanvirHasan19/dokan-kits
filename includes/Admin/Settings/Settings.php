<?php
namespace DokanKits\Admin\Settings;

use DokanKits\Core\Container;

/**
 * Settings Class
 *
 * @package DokanKits\Admin\Settings
 */
class Settings {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Settings API instance
     *
     * @var SettingsAPI
     */
    protected $api;

    /**
     * Tabs
     *
     * @var array
     */
    protected $tabs = [];

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
        
        // Try to get the API instance
        try {
            $this->api = $container->get( 'admin.settings.api' );
        } catch (\Exception $e) {
            // Create a basic API instance if not available in container
            $this->api = new SettingsAPI( $container );
        }
    }

    /**
     * Initialize settings
     *
     * @return void
     */
    public function init() {
        // Register settings
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        
        // Initialize tabs
        $this->init_tabs();
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        // Register settings group
        register_setting( 'dokan_kits_settings_group', 'dokan_kits_settings' );
        
        // Register individual settings (legacy support)
        $this->register_legacy_settings();
        
        /**
         * Action after settings registration
         *
         * @param Settings $this Settings instance
         */
        do_action( 'dokan_kits_settings_registered', $this );
    }

    /**
     * Register legacy settings for backward compatibility
     *
     * @return void
     */
    protected function register_legacy_settings() {
        // Legacy settings registration is important for backward compatibility
        $legacy_settings = [
            // Vendor
            'remove_vendor_checkbox',
            'set_default_seller_role_checkbox',
            'remove_become_a_vendor_button_checkbox',
            'enable_own_product_purchase_checkbox',
            
            // Product types
            'remove_variable_product_checkbox',
            'remove_external_product_checkbox',
            'remove_grouped_product_checkbox',
            
            // Product fields
            'remove_short_description_checkbox',
            'remove_long_description_checkbox',
            'remove_inventory_section_checkbox',
            'remove_geolocation_option_checkbox',
            'remove_shipping_tax_option_checkbox',
            'remove_linked_product_checkbox',
            'remove_attribute_variation_checkbox',
            'remove_bulk_discount_checkbox',
            'remove_rma_checkbox',
            'remove_wholesale_checkbox',
            'remove_min_max_product_checkbox',
            'remove_other_options_checkbox',
            'remove_product_advertisement_checkbox',
            'remove_catalog_mode_checkbox',
            'remove_downloadable_checkbox',
            'remove_virtual_checkbox',
            
            // Shipping
            'remove_split_shipping_checkbox',
            'remove_split_shipping_pro_checkbox',
            
            // Display
            'hide_add_to_cart_button_checkbox',
            'auto_complete_order_checkbox',
            
            // Advanced
            'enable_dimension_restrictions',
            'enable_size_restrictions',
            'image_max_width',
            'image_max_height',
            'image_max_size'
        ];
        
        foreach ( $legacy_settings as $option_name ) {
            register_setting( 'dokan_kits_settings_group', $option_name );
        }
    }

    /**
     * Initialize tabs
     *
     * @return void
     */
    protected function init_tabs() {
        try {
            $this->tabs = [
                'vendor'   => $this->container->get( 'admin.settings.tabs.vendor' ),
                'product'  => $this->container->get( 'admin.settings.tabs.product' ),
                'shipping' => $this->container->get( 'admin.settings.tabs.shipping' ),
                'display'  => $this->container->get( 'admin.settings.tabs.display' ),
                'advanced' => $this->container->get( 'admin.settings.tabs.advanced' ),
            ];
        } catch (\Exception $e) {
            // If tabs are not available, create placeholder tabs
            // This prevents fatal errors if some tabs are missing
            $this->tabs = [];
        }
        
        /**
         * Filter settings tabs
         *
         * @param array    $tabs Tabs
         * @param Settings $this Settings instance
         */
        $this->tabs = apply_filters( 'dokan_kits_settings_tabs', $this->tabs, $this );
    }

    /**
     * Get tabs
     *
     * @return array
     */
    public function get_tabs() {
        return $this->tabs;
    }

    /**
     * Get tab
     *
     * @param string $tab_id Tab ID
     *
     * @return mixed
     */
    public function get_tab( $tab_id ) {
        return isset( $this->tabs[ $tab_id ] ) ? $this->tabs[ $tab_id ] : null;
    }

    /**
     * Add tab
     *
     * @param string $tab_id Tab ID
     * @param mixed  $tab    Tab instance
     *
     * @return self
     */
    public function add_tab( $tab_id, $tab ) {
        $this->tabs[ $tab_id ] = $tab;
        
        return $this;
    }

    /**
     * Remove tab
     *
     * @param string $tab_id Tab ID
     *
     * @return self
     */
    public function remove_tab( $tab_id ) {
        if ( isset( $this->tabs[ $tab_id ] ) ) {
            unset( $this->tabs[ $tab_id ] );
        }
        
        return $this;
    }
    
    /**
     * Get container instance
     *
     * @return Container
     */
    public function get_container() {
        return $this->container;
    }
}