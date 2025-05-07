<?php
namespace DokanKits\Features\ProductFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Product Options Feature
 *
 * @package DokanKits\Features\ProductFeatures
 */
class ProductOptions extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Product Options', 'dokan-kits' );
        $this->description = __( 'Manage product options in the edit product form.', 'dokan-kits' );
        // No specific option key since this class manages multiple settings
        $this->option_key = null;
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // This feature doesn't have specific setup at the moment
        // It's included for future extensibility
        
        /**
         * Action after product options setup
         *
         * @param ProductOptions $this Feature instance
         */
        do_action( 'dokan_kits_product_options_setup', $this );
    }
}