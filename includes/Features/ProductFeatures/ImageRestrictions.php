<?php
namespace DokanKits\Features\ProductFeatures;

use DokanKits\Core\Container;

/**
 * Image Restrictions Feature
 *
 * @package DokanKits\Features\ProductFeatures
 */
class ImageRestrictions {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Container $container
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }
    
    /**
     * Initialize the feature
     *
     * @return void
     */
    public function init() {
        // Initialize the controller
        $this->container->get( 'controllers.image_restrictions' );
        
        // Add hooks for frontend enhancements if needed
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        // Maybe show notices to vendors
        add_action( 'dokan_dashboard_content_before', [ $this, 'maybe_show_notices' ] );
    }
    
    /**
     * Enqueue scripts for frontend
     * 
     * @return void
     */
    public function enqueue_scripts() {
        // Only load on vendor dashboard product edit page
        if ( function_exists( 'dokan_is_seller_dashboard' ) && dokan_is_seller_dashboard() ) {
            if ( get_option( 'enable_dimension_restrictions' ) === '1' || get_option( 'enable_size_restrictions' ) === '1' ) {
                // Enqueue your script to enhance the UI or show alerts
                wp_enqueue_script( 
                    'dokan-kits-image-restrictions', 
                    DOKAN_KITS_ASSETS_URL . '/js/image-restrictions.js', 
                    [ 'jquery' ], 
                    DOKAN_KITS_VERSION, 
                    true 
                );
                
                // Pass settings to JavaScript
                wp_localize_script( 'dokan-kits-image-restrictions', 'dokanKitsImageRestrictions', [
                    'enableDimensions' => get_option( 'enable_dimension_restrictions' ) === '1',
                    'requiredWidth' => intval( get_option( 'image_max_width', 800 ) ),
                    'requiredHeight' => intval( get_option( 'image_max_height', 800 ) ),
                    'enableSize' => get_option( 'enable_size_restrictions' ) === '1',
                    'maxSize' => floatval( get_option( 'image_max_size', 2 ) ),
                    'messages' => [
                        'dimensionError' => sprintf( 
                            __( 'Image dimensions must be exactly %d×%d pixels.', 'dokan-kits' ),
                            intval( get_option( 'image_max_width', 800 ) ),
                            intval( get_option( 'image_max_height', 800 ) )
                        ),
                        'sizeError' => sprintf(
                            __( 'File size exceeds the maximum limit of %s MB.', 'dokan-kits' ),
                            floatval( get_option( 'image_max_size', 2 ) )
                        ),
                    ],
                ] );
            }
        }
    }
    
    /**
     * Maybe show notices on the vendor dashboard
     * 
     * @return void
     */
    public function maybe_show_notices() {
        // Only show on product edit page
        if ( function_exists( 'dokan_is_seller_dashboard' ) && dokan_is_seller_dashboard() ) {
            $current_page = get_query_var( 'edit' ) ? 'edit' : get_query_var( 'page' );
            
            if ( $current_page === 'edit' || $current_page === 'new-product' ) {
                if ( get_option( 'enable_dimension_restrictions' ) === '1' ) {
                    echo '<div class="dokan-alert dokan-alert-info">';
                    printf(
                        __( 'Product images must be exactly %d×%d pixels.', 'dokan-kits' ),
                        intval( get_option( 'image_max_width', 800 ) ),
                        intval( get_option( 'image_max_height', 800 ) )
                    );
                    echo '</div>';
                }
                
                if ( get_option( 'enable_size_restrictions' ) === '1' ) {
                    echo '<div class="dokan-alert dokan-alert-info">';
                    printf(
                        __( 'Product images must not exceed %s MB in size.', 'dokan-kits' ),
                        floatval( get_option( 'image_max_size', 2 ) )
                    );
                    echo '</div>';
                }
            }
        }
    }
}