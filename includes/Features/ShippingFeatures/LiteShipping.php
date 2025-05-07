<?php
namespace DokanKits\Features\ShippingFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Lite Shipping Feature
 *
 * @package DokanKits\Features\ShippingFeatures
 */
class LiteShipping extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Lite Shipping', 'dokan-kits' );
        $this->description = __( 'Manage shipping options for Dokan Lite.', 'dokan-kits' );
        $this->option_key = 'remove_split_shipping_checkbox';
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
         * Action after lite shipping setup
         *
         * @param LiteShipping $this Feature instance
         */
        do_action( 'dokan_kits_lite_shipping_setup', $this );
    }
    
    /**
     * Remove split shipping
     *
     * @return void
     */
    protected function remove_split_shipping() {
        if ( ! function_exists( 'dokan_remove_hook_for_anonymous_class' ) || ! class_exists( 'WeDevs\Dokan\Shipping\Hooks' ) ) {
            return;
        }
        
        dokan_remove_hook_for_anonymous_class( 'woocommerce_cart_shipping_packages', 'WeDevs\Dokan\Shipping\Hooks', 'split_shipping_packages', 10 );
        dokan_remove_hook_for_anonymous_class( 'woocommerce_checkout_create_order_shipping_item', 'WeDevs\Dokan\Shipping\Hooks', 'add_shipping_pack_meta', 10 );
        dokan_remove_hook_for_anonymous_class( 'woocommerce_shipping_package_name', 'WeDevs\Dokan\Shipping\Hooks', 'change_shipping_pack_name', 10 );
        
        /**
         * Action after removing split shipping
         *
         * @param LiteShipping $this Feature instance
         */
        do_action( 'dokan_kits_after_remove_split_shipping', $this );
    }
}