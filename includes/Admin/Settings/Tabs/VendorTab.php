<?php
namespace DokanKits\Admin\Settings\Tabs;

use DokanKits\Core\Container;

/**
 * Vendor Tab
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
class VendorTab extends AbstractTab {
    /**
     * Initialize tab
     *
     * @return void
     */
    protected function init() {
        $this->id = 'vendor';
        $this->title = __( 'Vendor', 'dokan-kits' );
        $this->icon = 'fa-users';
        
        $this->settings = [
            [
                'id'          => 'remove_vendor_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Vendor Registration', 'dokan-kits' ),
                'description' => __( 'Remove "I am a vendor" option from the WooCommerce my account page.', 'dokan-kits' ),
                'icon'        => 'fa-users'
            ],
            [
                'id'          => 'set_default_seller_role_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Enable "I am a Vendor" by default', 'dokan-kits' ),
                'description' => __( 'To enable the "I am a Vendor" option by default on the My Account page.', 'dokan-kits' ),
                'icon'        => 'fa-user-check'
            ],
            [
                'id'          => 'remove_become_a_vendor_button_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Become a Vendor Button', 'dokan-kits' ),
                'description' => __( 'Remove Become a Vendor button from the WooCommerce My Account page.', 'dokan-kits' ),
                'icon'        => 'fa-user-times'
            ],
            [
                'id'          => 'enable_own_product_purchase_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Enable Purchase of Own Products', 'dokan-kits' ),
                'description' => __( 'Allow admin and vendors to purchase their own products.', 'dokan-kits' ),
                'icon'        => 'fa-cart-flatbed-suitcase'
            ],
        ];
    }
}