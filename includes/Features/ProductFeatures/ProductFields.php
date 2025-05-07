<?php
namespace DokanKits\Features\ProductFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Product Fields Feature
 *
 * @package DokanKits\Features\ProductFeatures
 */
class ProductFields extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Product Fields', 'dokan-kits' );
        $this->description = __( 'Manage product fields in the edit product form.', 'dokan-kits' );
        // No specific option key since this class manages multiple settings
        $this->option_key = null;
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // Check which fields should be removed
        $this->remove_fields();
        
        /**
         * Action after product fields setup
         *
         * @param ProductFields $this Feature instance
         */
        do_action( 'dokan_kits_product_fields_setup', $this );
    }
    
    /**
     * Remove fields based on settings
     *
     * @return void
     */
    protected function remove_fields() {
        // Add CSS to hide fields in the edit product form
        add_action( 'wp_head', [ $this, 'add_css_to_hide_fields' ] );
        
        /**
         * Action after removing fields
         *
         * @param ProductFields $this Feature instance
         */
        do_action( 'dokan_kits_after_remove_product_fields', $this );
    }
    
    /**
     * Add CSS to hide fields
     *
     * @return void
     */
    public function add_css_to_hide_fields() {
        // Only add CSS in frontend, not in admin
        if ( is_admin() ) {
            return;
        }
        
        // Define CSS rules based on settings
        $css_rules = $this->get_css_rules();
        
        // Output CSS if there are rules
        if ( ! empty( $css_rules ) ) {
            echo '<style>' . $css_rules . '</style>';
        }
    }
    
    /**
     * Get CSS rules to hide fields
     *
     * @return string CSS rules
     */
    protected function get_css_rules() {
        $css = '';
        
        // Map option keys to selectors
        $fields = [
            'remove_short_description_checkbox' => '.dokan-product-short-description',
            'remove_long_description_checkbox' => '.dokan-product-description',
            'remove_inventory_section_checkbox' => '.dokan-product-inventory',
            'remove_geolocation_option_checkbox' => '.dokan-geolocation-options',
            'remove_shipping_tax_option_checkbox' => '.dokan-product-shipping-tax',
            'remove_linked_product_checkbox' => '.dokan-linked-product-options',
            'remove_attribute_variation_checkbox' => '.dokan-attribute-variation-options',
            'remove_bulk_discount_checkbox' => '.dokan-discount-options',
            'remove_rma_checkbox' => '.dokan-rma-options',
            'remove_wholesale_checkbox' => '.dokan-wholesale-options',
            'remove_min_max_product_checkbox' => '.dokan-order-min-max-product-metabox-wrapper',
            'remove_other_options_checkbox' => '.dokan-other-options',
            'remove_product_advertisement_checkbox' => '.dokan-proudct-advertisement',
            'remove_catalog_mode_checkbox' => '.dokan-catalog-mode',
            'remove_downloadable_checkbox' => '.downloadable-checkbox',
            'remove_virtual_checkbox' => '.virtual-checkbox',
        ];
        
        // Check each option and add CSS rule if enabled
        foreach ( $fields as $option => $selector ) {
            if ( get_option( $option ) === '1' ) {
                $css .= '.dokan-product-edit-form ' . $selector . ' { display: none !important; }';
            }
        }
        
        /**
         * Filter CSS rules for hiding fields
         *
         * @param string       $css   CSS rules
         * @param ProductFields $this Feature instance
         */
        return apply_filters( 'dokan_kits_product_fields_css', $css, $this );
    }
}