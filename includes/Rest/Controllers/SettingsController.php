<?php
namespace DokanKits\Rest\Controllers;

use DokanKits\Core\Container;
use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use WP_Error;

/**
 * Settings REST Controller
 *
 * @package DokanKits\Rest\Controllers
 */
class SettingsController extends WP_REST_Controller {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Endpoint namespace
     *
     * @var string
     */
    protected $namespace = 'dokan-kits/v1';

    /**
     * Route base
     *
     * @var string
     */
    protected $rest_base = 'settings';

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route( 
            $this->namespace, 
            '/' . $this->rest_base, 
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'get_items_permissions_check' ],
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_items' ],
                    'permission_callback' => [ $this, 'update_items_permissions_check' ],
                ],
                'schema' => [ $this, 'get_item_schema' ],
            ]
        );
    }

    /**
     * Check if a given request has access to get items
     *
     * @param \WP_REST_Request $request Full data about the request
     *
     * @return \WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Check if a given request has access to update items
     *
     * @param \WP_REST_Request $request Full data about the request
     *
     * @return \WP_Error|bool
     */
    public function update_items_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Get settings
     *
     * @param \WP_REST_Request $request Full data about the request
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_items( $request ) {
        try {
            $settings_api = $this->container->get( 'admin.settings.api' );
            $settings = $settings_api->get_all();
            
            // Get legacy settings
            $legacy_settings = $this->get_legacy_settings();
            
            // Merge settings
            $settings = array_merge( $settings, $legacy_settings );
            
            return rest_ensure_response( $settings );
        } catch (\Exception $e) {
            return new WP_Error(
                'dokan_kits_settings_error',
                $e->getMessage(),
                [ 'status' => 500 ]
            );
        }
    }

    /**
     * Update settings
     *
     * @param \WP_REST_Request $request Full data about the request
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function update_items( $request ) {
        $settings = $request->get_json_params();
        
        if ( empty( $settings ) ) {
            return new WP_Error(
                'dokan_kits_settings_empty',
                __( 'No settings provided', 'dokan-kits' ),
                [ 'status' => 400 ]
            );
        }
        
        try {
            $settings_api = $this->container->get( 'admin.settings.api' );
            $updated = $settings_api->update_all( $settings );
            
            if ( ! $updated ) {
                return new WP_Error(
                    'dokan_kits_settings_update_failed',
                    __( 'Failed to update settings', 'dokan-kits' ),
                    [ 'status' => 500 ]
                );
            }
            
            return rest_ensure_response( [
                'success' => true,
                'message' => __( 'Settings updated successfully', 'dokan-kits' ),
                'settings' => $settings_api->get_all(),
            ] );
        } catch (\Exception $e) {
            return new WP_Error(
                'dokan_kits_settings_error',
                $e->getMessage(),
                [ 'status' => 500 ]
            );
        }
    }

    /**
     * Get legacy settings
     *
     * @return array
     */
    protected function get_legacy_settings() {
        $legacy_settings = [];
        
        // Get all option names
        $legacy_options = [
            'remove_vendor_checkbox',
            'set_default_seller_role_checkbox',
            'remove_become_a_vendor_button_checkbox',
            'enable_own_product_purchase_checkbox',
            'remove_variable_product_checkbox',
            'remove_external_product_checkbox',
            'remove_grouped_product_checkbox',
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
            'remove_split_shipping_checkbox',
            'remove_split_shipping_pro_checkbox',
            'hide_add_to_cart_button_checkbox',
            'auto_complete_order_checkbox',
            'enable_dimension_restrictions',
            'enable_size_restrictions',
            'image_max_width',
            'image_max_height',
            'image_max_size',
        ];
        
        foreach ( $legacy_options as $option ) {
            $legacy_settings[ $option ] = get_option( $option );
        }
        
        return $legacy_settings;
    }

    /**
     * Get item schema
     *
     * @return array
     */
    public function get_item_schema() {
        return [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'settings',
            'type'       => 'object',
            'properties' => [
                'dokan_kits_settings' => [
                    'description' => __( 'Settings', 'dokan-kits' ),
                    'type'        => 'object',
                ],
            ],
        ];
    }
}