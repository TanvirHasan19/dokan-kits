<?php
namespace DokanKits\Admin\Settings\Tabs;

/**
 * Advanced Tab
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
class AdvancedTab extends AbstractTab {
    /**
     * Initialize tab
     *
     * @return void
     */
    protected function init() {
        $this->id = 'advanced';
        $this->title = __( 'Advanced', 'dokan-kits' );
        $this->icon = 'fa-gear';
        
        $this->settings = [];
        
        // Add image restriction options
        $this->add_image_restrictions();
        
        // Add other settings
        // ...
    }
    
    /**
     * Add image restriction options
     *
     * @return void
     */
    private function add_image_restrictions() {
        $dimension_enabled = get_option('enable_dimension_restrictions') === '1';
        $size_enabled = get_option('enable_size_restrictions') === '1';
        
        $this->settings[] = [
            'id'    => 'image_restrictions_heading',
            'type'  => 'heading',
            'title' => __( 'Product Image Restrictions', 'dokan-kits' ),
        ];
        
        // Dimension restrictions group
        $this->settings[] = [
            'id'          => 'dimension_restrictions_group',
            'type'        => 'group',
            'title'       => __( 'Product Image Dimension Restrictions', 'dokan-kits' ),
            'description' => __( 'Set exact dimension requirements for vendor product images.', 'dokan-kits' ),
            'icon'        => 'fa-crop',
            'fields'      => [
                [
                    'id'    => 'enable_dimension_restrictions',
                    'title' => __( 'Enable Dimension Restrictions', 'dokan-kits' )
                ],
                [
                    'id'    => 'image_max_width',
                    'title' => __( 'Required Width', 'dokan-kits' ),
                    'type'  => 'number',
                    'default' => 800,
                    'min'   => 1,
                    'suffix' => 'px',
                    'dependency' => [
                        'id'    => 'enable_dimension_restrictions',
                        'value' => '1',
                    ],
                ],
                [
                    'id'    => 'image_max_height',
                    'title' => __( 'Required Height', 'dokan-kits' ),
                    'type'  => 'number',
                    'default' => 800,
                    'min'   => 1,
                    'suffix' => 'px',
                    'dependency' => [
                        'id'    => 'enable_dimension_restrictions',
                        'value' => '1',
                    ],
                ]
            ]
        ];
        
        // Size restrictions
        $this->settings[] = [
            'id'          => 'size_restrictions_group',
            'type'        => 'group',
            'title'       => __( 'Product Image Size Restriction', 'dokan-kits' ),
            'description' => __( 'Set maximum file size limit for vendor product images.', 'dokan-kits' ),
            'icon'        => 'fa-file-image',
            'fields'      => [
                [
                    'id'    => 'enable_size_restrictions',
                    'title' => __( 'Enable Size Restrictions', 'dokan-kits' )
                ],
                [
                    'id'    => 'image_max_size',
                    'title' => __( 'Maximum File Size', 'dokan-kits' ),
                    'type'  => 'number',
                    'default' => 2,
                    'min'   => 0.1,
                    'step'  => 0.1,
                    'suffix' => 'MB',
                    'dependency' => [
                        'id'    => 'enable_size_restrictions',
                        'value' => '1',
                    ],
                ]
            ]
        ];
    }
}