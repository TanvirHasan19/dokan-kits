<?php
namespace DokanKits\Features\VendorFeatures;

use DokanKits\Features\AbstractFeature;

/**
 * Vendor Registration Feature
 *
 * @package DokanKits\Features\VendorFeatures
 */
class VendorRegistration extends AbstractFeature {
    /**
     * Class constructor
     *
     * @param \DokanKits\Core\Container $container
     */
    public function __construct( $container ) {
        parent::__construct( $container );

        $this->name = __( 'Vendor Registration', 'dokan-kits' );
        $this->description = __( 'Remove "I am a vendor" option from the WooCommerce my account page.', 'dokan-kits' );
        $this->option_key = 'remove_vendor_checkbox';
    }

    /**
     * Setup the feature
     *
     * @return void
     */
    protected function setup() {
        // Remove vendor registration form field
        add_action( 'init', [ $this, 'remove_vendor_registration' ], 20 );

        // Default seller role filter - applied separately from above
        if ( get_option( 'set_default_seller_role_checkbox' ) === '1' ) {
            add_filter( 'dokan_seller_registration_default_role', [ $this, 'set_seller_as_default' ] );
        }
    }

    /**
     * Remove vendor registration functionality
     *
     * @return void
     */
    public function remove_vendor_registration() {
        if ( ! class_exists( 'WeDevs\Dokan\Registration' ) ) {
            return;
        }

        // Remove Dokan's custom registration form fields
        remove_action( 'woocommerce_register_form', 'dokan_seller_reg_form_fields', 10 );

        // Remove Dokan's registration validation
        if ( isset( dokan()->registration ) ) {
            remove_filter( 'woocommerce_process_registration_errors', [ dokan()->registration, 'validate_registration' ], 10 );
            remove_filter( 'woocommerce_registration_errors', [ dokan()->registration, 'validate_registration' ], 10 );
        }

        /**
         * Action after removing vendor registration
         *
         * @param VendorRegistration $this Feature instance
         */
        do_action( 'dokan_kits_after_remove_vendor_registration', $this );
    }

    /**
     * Set seller as default role
     *
     * @return string
     */
    public function set_seller_as_default() {
        return 'seller';
    }

    /**
     * Get additional settings for this feature
     *
     * @return array
     */
    public function get_settings() {
        return [
            [
                'id'          => 'set_default_seller_role_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Enable "I am a Vendor" by default', 'dokan-kits' ),
                'description' => __( 'To enable the "I am a Vendor" option by default on the My Account page.', 'dokan-kits' ),
                'icon'        => 'fa-user-check'
            ]
        ];
    }
}