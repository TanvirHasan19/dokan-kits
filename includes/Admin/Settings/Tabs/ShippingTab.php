<?php
namespace DokanKits\Admin\Settings\Tabs;

use DokanKits\Core\Container;

/**
 * Shipping Tab
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
class ShippingTab extends AbstractTab {
    /**
     * Initialize tab
     *
     * @return void
     */
    protected function init() {
        $this->id = 'shipping';
        $this->title = __( 'Shipping', 'dokan-kits' );
        $this->icon = 'fa-truck';
        
        $this->settings = [
            [
                'id'          => 'remove_split_shipping_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Split Shipping Dokan Lite', 'dokan-kits' ),
                'description' => __( 'Remove split shipping from the WooCommerce cart and checkout page using the Dokan Lite plugin.', 'dokan-kits' ),
                'icon'        => 'fa-truck'
            ],
            [
                'id'          => 'remove_split_shipping_pro_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Split Shipping Dokan Pro', 'dokan-kits' ),
                'description' => __( 'Remove Split Shipping from the WooCommerce Cart and Checkout page using the Dokan Pro plugin.', 'dokan-kits' ),
                'icon'        => 'fa-dolly'
            ],
        ];
    }
}