<?php
namespace DokanKits\Features\CartFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Cart Buttons Feature
 *
 * @package DokanKits\Features\CartFeatures
 */
class CartButtons extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Cart Buttons', 'dokan-kits' );
        $this->description = __( 'Manage cart buttons and order status.', 'dokan-kits' );
        $this->option_key = 'hide_add_to_cart_button_checkbox';
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // Hide add to cart buttons
        $this->hide_add_to_cart_buttons();
        
        // Auto-complete virtual/downloadable orders if enabled
        if ( get_option( 'auto_complete_order_checkbox' ) === '1' ) {
            add_filter( 'woocommerce_order_item_needs_processing', [ $this, 'auto_complete_virtual_downloadable_orders' ], 10, 3 );
        }
        
        /**
         * Action after cart buttons setup
         *
         * @param CartButtons $this Feature instance
         */
        do_action( 'dokan_kits_cart_buttons_setup', $this );
    }
    
    /**
     * Hide add to cart buttons
     *
     * @return void
     */
    protected function hide_add_to_cart_buttons() {
        // Remove add to cart button from product loops
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        
        // Remove add to cart button from single product pages
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

        // Add custom CSS to hide other add to cart buttons
        add_action( 'wp_head', [ $this, 'add_hide_button_css' ] );
        
        /**
         * Action after hiding add to cart buttons
         *
         * @param CartButtons $this Feature instance
         */
        do_action( 'dokan_kits_after_hide_add_to_cart_buttons', $this );
    }
    
    /**
     * Add CSS to hide add to cart buttons
     *
     * @return void
     */
    public function add_hide_button_css() {
        // Only add CSS in frontend, not in admin
        if ( is_admin() ) {
            return;
        }
        
        $css = "
            .single-product .product-type-simple .single_add_to_cart_button,
            .single-product .product-type-variable .single_add_to_cart_button,
            .product .add_to_cart_button {
                display: none !important;
            }
        ";
        
        /**
         * Filter CSS for hiding add to cart buttons
         *
         * @param string     $css  CSS rules
         * @param CartButtons $this Feature instance
         */
        $css = apply_filters( 'dokan_kits_hide_add_to_cart_css', $css, $this );
        
        echo '<style>' . $css . '</style>';
    }
    
    /**
     * Auto-complete virtual and downloadable orders
     *
     * @param bool        $needs_processing Whether the item needs processing
     * @param \WC_Product $product         Product object
     * @param int         $order_id        Order ID
     *
     * @return bool
     */
    public function auto_complete_virtual_downloadable_orders( $needs_processing, $product, $order_id ) {
        if ( $product->is_virtual() || $product->is_downloadable() ) {
            return false; // Auto-complete order
        }
        
        return $needs_processing; // Keep default behavior for other products
    }
}