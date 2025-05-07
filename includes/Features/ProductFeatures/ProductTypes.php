<?php
namespace DokanKits\Features\ProductFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Product Types Feature
 *
 * @package DokanKits\Features\ProductFeatures
 */
class ProductTypes extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Product Types', 'dokan-kits' );
        $this->description = __( 'Manage available product types in Dokan.', 'dokan-kits' );
        // No specific option key since this class manages multiple settings
        $this->option_key = null;
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // Register filter to modify available product types
        add_filter( 'dokan_product_types', [ $this, 'filter_product_types' ], 11 );
        
        /**
         * Action after product types setup
         *
         * @param ProductTypes $this Feature instance
         */
        do_action( 'dokan_kits_product_types_setup', $this );
    }
    
    /**
     * Filter product types based on settings
     *
     * @param array $product_types Available product types
     *
     * @return array Modified product types
     */
    public function filter_product_types( $product_types ) {
        // Remove variable product type if enabled
        if ( get_option( 'remove_variable_product_checkbox' ) === '1' ) {
            unset( $product_types['variable'] );
        }
        
        // Remove external product type if enabled
        if ( get_option( 'remove_external_product_checkbox' ) === '1' ) {
            unset( $product_types['external'] );
        }
        
        // Remove grouped product type if enabled
        if ( get_option( 'remove_grouped_product_checkbox' ) === '1' ) {
            unset( $product_types['grouped'] );
        }
        
        /**
         * Filter to modify product types
         *
         * @param array       $product_types Modified product types
         * @param ProductTypes $this         Feature instance
         */
        return apply_filters( 'dokan_kits_filter_product_types', $product_types, $this );
    }
}