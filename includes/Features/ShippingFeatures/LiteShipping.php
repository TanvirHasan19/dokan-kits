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
	     add_action( 'init', [ $this, 'remove_split_shipping' ], 10000000000000 );
        
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
    public function remove_split_shipping() {
        if ( ! function_exists( 'dokan_remove_hook_for_anonymous_class' ) ) {
            return;
        }

	    remove_all_filters( 'woocommerce_cart_shipping_packages' );
	    remove_all_actions( 'woocommerce_checkout_create_order_shipping_item');
	    remove_all_filters( 'woocommerce_shipping_package_name' );

        /**
         * Action after removing split shipping
         *
         * @param LiteShipping $this Feature instance
         */
        do_action( 'dokan_kits_after_remove_split_shipping', $this );
    }
}