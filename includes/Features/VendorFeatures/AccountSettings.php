<?php
namespace DokanKits\Features\VendorFeatures;

use DokanKits\Core\Container;
use DokanKits\Features\AbstractFeature;

/**
 * Account Settings Feature
 *
 * @package DokanKits\Features\VendorFeatures
 */
class AccountSettings extends AbstractFeature {
    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        parent::__construct( $container );
        
        $this->name = __( 'Account Settings', 'dokan-kits' );
        $this->description = __( 'Manage vendor account settings.', 'dokan-kits' );
        $this->option_key = 'remove_become_a_vendor_button_checkbox';
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // Remove "Become a Vendor" button
        $this->remove_become_a_vendor_button();
        
        /**
         * Action after account settings setup
         *
         * @param AccountSettings $this Feature instance
         */
        do_action( 'dokan_kits_account_settings_setup', $this );
    }
    
    /**
     * Remove "Become a Vendor" button
     *
     * @return void
     */
    protected function remove_become_a_vendor_button() {
        if ( class_exists( '\WeDevs\Dokan\Vendor\Hooks\VendorRegistration' ) && isset( dokan()->frontend_manager->become_a_vendor ) ) {
            remove_action( 'woocommerce_after_my_account', [ dokan()->frontend_manager->become_a_vendor, 'render_become_a_vendor_section' ] );
        }
        
        /**
         * Action after removing "Become a Vendor" button
         *
         * @param AccountSettings $this Feature instance
         */
        do_action( 'dokan_kits_after_remove_become_vendor_button', $this );
    }
}