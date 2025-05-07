<?php
namespace DokanKits\Features\ShippingFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Pro Shipping Feature
 *
 * @package DokanKits\Features\ShippingFeatures
 */
class ProShipping extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Pro Shipping', 'dokan-kits' );
        $this->description = __( 'Manage shipping options for Dokan Pro.', 'dokan-kits' );
        $this->option_key = 'remove_split_shipping_pro_checkbox';
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // Remove split shipping
        $this->remove_split_shipping();
        
        /**
         * Action after pro shipping setup
         *
         * @param ProShipping $this Feature instance
         */
        do_action( 'dokan_kits_pro_shipping_setup', $this );
    }
    
    /**
     * Remove split shipping
     *
     * @return void
     */
    protected function remove_split_shipping() {
        remove_filter( 'woocommerce_cart_shipping_packages', 'dokan_custom_split_shipping_packages' );
        remove_filter( 'woocommerce_shipping_package_name', 'dokan_change_shipping_pack_name' );
        remove_action( 'woocommerce_checkout_create_order_shipping_item', 'dokan_add_shipping_pack_meta' );
        
        /**
         * Action after removing split shipping
         *
         * @param ProShipping $this Feature instance
         */
        do_action( 'dokan_kits_after_remove_pro_split_shipping', $this );
    }
}