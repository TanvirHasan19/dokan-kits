<?php
namespace DokanKits\Features\VendorFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Vendor Capabilities Feature
 *
 * @package DokanKits\Features\VendorFeatures
 */
class VendorCapabilities extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Vendor Capabilities', 'dokan-kits' );
        $this->description = __( 'Manage vendor capabilities and permissions.', 'dokan-kits' );
        $this->option_key = 'enable_own_product_purchase_checkbox';
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // Enable purchasing of own products
        $this->enable_own_product_purchase();
        
        /**
         * Action after vendor capabilities setup
         *
         * @param VendorCapabilities $this Feature instance
         */
        do_action( 'dokan_kits_vendor_capabilities_setup', $this );
    }
    
    /**
     * Enable purchasing of own products
     *
     * @return void
     */
    protected function enable_own_product_purchase() {
        // Remove filters that restrict vendors from purchasing their own products
        if ( function_exists( 'dokan_vendor_own_product_purchase_restriction' ) ) {
            remove_filter( 'woocommerce_is_purchasable', 'dokan_vendor_own_product_purchase_restriction', 10 );
        }
        
        if ( function_exists( 'dokan_vendor_product_review_restriction' ) ) {
            remove_filter( 'woocommerce_product_review_comment_form_args', 'dokan_vendor_product_review_restriction' );
        }
        
        /**
         * Action after enabling own product purchase
         *
         * @param VendorCapabilities $this Feature instance
         */
        do_action( 'dokan_kits_after_enable_own_product_purchase', $this );
    }
}