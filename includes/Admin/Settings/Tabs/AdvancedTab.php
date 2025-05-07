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
        
        // Dimension restrictions
        $this->settings[] = [
            'id'          => 'enable_dimension_restrictions',
            'type'        => 'toggle',
            'title'       => __( 'Product Image Dimension Restrictions', 'dokan-kits' ),
            'description' => __( 'Set exact dimension requirements for vendor product images.', 'dokan-kits' ),
            'icon'        => 'fa-crop'
        ];
        
        // Dimension settings (only visible when enabled)
        $this->settings[] = [
            'id'    => 'image_dimension_settings',
            'type'  => 'custom',
            'dependency' => [
                'id'    => 'enable_dimension_restrictions',
                'value' => '1',
            ],
            'renderer' => function() {
                ?>
                <div class="dimension-settings-container" style="padding: 10px 0 20px 30px;">
                    <div class="restriction-input" style="margin-bottom: 10px;">
                        <label><?php _e('Required Width (px):', 'dokan-kits'); ?>
                            <input type="number" name="image_max_width" value="<?php echo esc_attr(get_option('image_max_width', 800)); ?>" min="1">
                        </label>
                    </div>
                    <div class="restriction-input">
                        <label><?php _e('Required Height (px):', 'dokan-kits'); ?>
                            <input type="number" name="image_max_height" value="<?php echo esc_attr(get_option('image_max_height', 800)); ?>" min="1">
                        </label>
                    </div>
                </div>
                <?php
            }
        ];
        
        // Size restrictions
        $this->settings[] = [
            'id'          => 'enable_size_restrictions',
            'type'        => 'toggle',
            'title'       => __( 'Product Image Size Restriction', 'dokan-kits' ),
            'description' => __( 'Set maximum file size limit for vendor product images.', 'dokan-kits' ),
            'icon'        => 'fa-file-image'
        ];
        
        // Size settings (only visible when enabled)
        $this->settings[] = [
            'id'    => 'image_size_settings',
            'type'  => 'custom',
            'dependency' => [
                'id'    => 'enable_size_restrictions',
                'value' => '1',
            ],
            'renderer' => function() {
                ?>
                <div class="size-settings-container" style="padding: 10px 0 20px 30px;">
                    <div class="restriction-input">
                        <label><?php _e('Maximum File Size (MB):', 'dokan-kits'); ?>
                            <input type="number" name="image_max_size" value="<?php echo esc_attr(get_option('image_max_size', 2)); ?>" min="0.1" step="0.1">
                        </label>
                    </div>
                </div>
                <?php
            }
        ];
    }
}