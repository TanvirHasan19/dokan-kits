<?php
namespace DokanKits\Admin\Settings\Tabs;

use DokanKits\Core\Container;

/**
 * Product Tab
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
class ProductTab extends AbstractTab {
    /**
     * Initialize tab
     *
     * @return void
     */
    protected function init() {
        $this->id = 'product';
        $this->title = __( 'Product', 'dokan-kits' );
        $this->icon = 'fa-box';
        
        $this->settings = [
            [
                'id'          => 'product_types',
                'type'        => 'group',
                'title'       => __( 'Remove Product Types', 'dokan-kits' ),
                'description' => __( 'Remove product types from the Dokan single product page', 'dokan-kits' ),
                'icon'        => 'fa-tags',
                'fields'      => [
                    [
                        'id'    => 'remove_variable_product_checkbox',
                        'title' => __( 'Variable', 'dokan-kits' )
                    ],
                    [
                        'id'    => 'remove_external_product_checkbox',
                        'title' => __( 'External', 'dokan-kits' )
                    ],
                    [
                        'id'    => 'remove_grouped_product_checkbox',
                        'title' => __( 'Grouped', 'dokan-kits' )
                    ]
                ]
            ],
            [
                'id'          => 'remove_short_description_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Short Description', 'dokan-kits' ),
                'description' => __( 'Remove short description from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-file-lines'
            ],
            [
                'id'          => 'remove_long_description_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Long Description', 'dokan-kits' ),
                'description' => __( 'Remove long description from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-file-text'
            ],
            [
                'id'          => 'remove_inventory_section_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Inventory Section', 'dokan-kits' ),
                'description' => __( 'Remove inventory section from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-file-text'
            ],
            [
                'id'          => 'remove_geolocation_option_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Geolocation Option', 'dokan-kits' ),
                'description' => __( 'Remove geolocation option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-location-dot'
            ],
            [
                'id'          => 'remove_shipping_tax_option_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Product Shipping Tax Option', 'dokan-kits' ),
                'description' => __( 'Remove product shipping tax option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-truck-fast'
            ],
            [
                'id'          => 'remove_linked_product_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Linked Product Option', 'dokan-kits' ),
                'description' => __( 'Remove linked product option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-link'
            ],
            [
                'id'          => 'remove_attribute_variation_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Attribute and Variation Option', 'dokan-kits' ),
                'description' => __( 'Remove Attribute and Variation option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-sitemap'
            ],
            [
                'id'          => 'remove_bulk_discount_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Bulk Discount Option', 'dokan-kits' ),
                'description' => __( 'Remove bulk discount option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-percent'
            ],
            [
                'id'          => 'remove_rma_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove RMA Option', 'dokan-kits' ),
                'description' => __( 'Remove RMA option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-rotate-left'
            ],
            [
                'id'          => 'remove_wholesale_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Wholesale Option', 'dokan-kits' ),
                'description' => __( 'Remove wholesale option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-box'
            ],
            [
                'id'          => 'remove_min_max_product_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Min Max Product Option', 'dokan-kits' ),
                'description' => __( 'Remove min max product option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-arrows-up-down'
            ],
            [
                'id'          => 'remove_other_options_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Other Options', 'dokan-kits' ),
                'description' => __( 'Remove other options from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-gear'
            ],
            [
                'id'          => 'remove_product_advertisement_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Product Advertisement Option', 'dokan-kits' ),
                'description' => __( 'Remove product advertisement option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-bullhorn'
            ],
            [
                'id'          => 'remove_catalog_mode_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Catalog Mode Option', 'dokan-kits' ),
                'description' => __( 'Remove catalog mode option from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-store'
            ],
            [
                'id'          => 'remove_downloadable_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Downloadable Checkbox', 'dokan-kits' ),
                'description' => __( 'Remove downloadable checkbox from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-download'
            ],
            [
                'id'          => 'remove_virtual_checkbox',
                'type'        => 'toggle',
                'title'       => __( 'Remove Virtual Checkbox', 'dokan-kits' ),
                'description' => __( 'Remove virtual checkbox from the edit product form.', 'dokan-kits' ),
                'icon'        => 'fa-cloud'
            ]
            // Add more product settings as needed
        ];
    }
}