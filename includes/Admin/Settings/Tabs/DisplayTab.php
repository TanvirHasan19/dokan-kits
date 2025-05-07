<?php
namespace DokanKits\Admin\Settings\Tabs;

use DokanKits\Core\Container;

/**
 * Display Tab
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
class DisplayTab extends AbstractTab {
    /**
     * Initialize tab
     *
     * @return void
     */
    protected function init() {
        $this->id = 'display';
        $this->title = __( 'Display', 'dokan-kits' );
        $this->icon = 'fa-desktop';
        
        $this->settings = [
            [
                'id'          => 'hide_add_to_cart_button_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Hide Add to Cart Button', 'dokan-kits' ),
                'description' => __( 'Hide Add to Cart Button From WooCommerce Product Page.', 'dokan-kits' ),
                'icon'        => 'fa-shopping-cart'
            ],
            [
                'id'          => 'auto_complete_order_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Manage Order Status', 'dokan-kits' ),
                'description' => __( 'Enable the button to auto-complete virtual and downloadable order statuses.', 'dokan-kits' ),
                'icon'        => 'fa-tasks'
            ],
        ];
    }
}