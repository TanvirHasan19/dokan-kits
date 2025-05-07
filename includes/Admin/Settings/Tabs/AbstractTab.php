<?php
namespace DokanKits\Admin\Settings\Tabs;

use DokanKits\Core\Container;

/**
 * Abstract Tab
 *
 * @package DokanKits\Admin\Settings\Tabs
 */
abstract class AbstractTab implements TabInterface {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Tab ID
     *
     * @var string
     */
    protected $id;

    /**
     * Tab title
     *
     * @var string
     */
    protected $title;

    /**
     * Tab icon
     *
     * @var string
     */
    protected $icon;

    /**
     * Tab settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
        $this->init();
    }

    /**
     * Initialize tab
     *
     * @return void
     */
    abstract protected function init();

    /**
     * Get tab ID
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get tab title
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Get tab icon
     *
     * @return string
     */
    public function get_icon() {
        return $this->icon;
    }

    /**
     * Get tab settings
     *
     * @return array
     */
    public function get_settings() {
        /**
         * Filter tab settings
         *
         * @param array        $settings Tab settings
         * @param AbstractTab  $this     Tab instance
         */
        return apply_filters( 'dokan_kits_settings_tab_' . $this->get_id() . '_settings', $this->settings, $this );
    }

    /**
     * Render tab content
     *
     * @return void
     */
    public function render() {
        // Start the grid
        echo '<div id="dokan-kits-body-content" class="tab-container">';
        
        // Render settings
        $this->render_settings();
        
        echo '</div>';
    }

    /**
     * Render settings
     *
     * @return void
     */
    protected function render_settings() {
        $settings = $this->get_settings();
        
        if (empty($settings)) {
            echo '<div class="dokan-kits-empty-tab">';
            echo '<p>' . __('No settings available for this tab.', 'dokan-kits') . '</p>';
            echo '</div>';
            return;
        }
        
        foreach ( $settings as $setting ) {
            $this->render_setting( $setting );
        }
    }

    /**
     * Render a setting
     *
     * @param array $setting Setting data
     *
     * @return void
     */
    protected function render_setting( $setting ) {
        // Get setting type
        $type = isset( $setting['type'] ) ? $setting['type'] : 'toggle';
        
        // Get renderer method
        $renderer = 'render_' . $type . '_setting';
        
        // Check if renderer exists
        if ( method_exists( $this, $renderer ) ) {
            $this->$renderer( $setting );
        } else {
            /**
             * Action to render custom setting type
             *
             * @param array        $setting Setting data
             * @param AbstractTab  $this    Tab instance
             */
            do_action( 'dokan_kits_render_setting_' . $type, $setting, $this );
        }
    }

    /**
     * Render toggle setting
     *
     * @param array $setting Setting data
     *
     * @return void
     */
    protected function render_toggle_setting( $setting ) {
        // Get option value
        $option_name = $setting['id'];
        $option_value = get_option( $option_name, 0 );
        $disabled = isset( $setting['disabled'] ) && $setting['disabled'] ? 'disabled' : '';
        
        ?>
        <div class="dokan_kits_style_box">
            <i class="fa <?php echo esc_attr( $setting['icon'] ); ?> fa-3x"></i>
            <div class="toggle-label">
                <label for="<?php echo esc_attr( $option_name ); ?>" class="for_title_label">
                    <?php echo esc_html( $setting['title'] ); ?>
                </label>
                <label class="switch">
                    <input 
                        type="checkbox" 
                        id="<?php echo esc_attr( $option_name ); ?>" 
                        name="<?php echo esc_attr( $option_name ); ?>" 
                        value="1" 
                        <?php checked( $option_value, 1 ); ?> 
                        <?php echo $disabled; ?>
                    >
                    <span class="slider"></span>
                </label>
                <span class="status-text"><?php echo $option_value ? __( 'Active', 'dokan-kits' ) : __( 'Inactive', 'dokan-kits' ); ?></span>
            </div>
            <p class="additional-text"><?php echo esc_html( $setting['description'] ); ?></p>
        </div>
        <?php
    }

    /**
     * Render group setting
     *
     * @param array $setting Setting data
     *
     * @return void
     */
    protected function render_group_setting( $setting ) {
        ?>
        <div class="seperate-style-for-box">
            <i class="fa <?php echo esc_attr( $setting['icon'] ); ?> fa-3x"></i>
            <div class="toggle-label">
                <label class="for_title_label"><?php echo esc_html( $setting['title'] ); ?></label>
                <div class="additional-text"><?php echo esc_html( $setting['description'] ); ?></div>
                
                <div class="toggle-group re_product_toggle">
                    <?php foreach ( $setting['fields'] as $field ) : 
                        $option_name = $field['id'];
                        $option_value = get_option( $option_name, 0 );
                    ?>
                    <div class="type-bu-si">
                        <label for="<?php echo esc_attr( $option_name ); ?>" class="for_title_label">
                            <?php echo esc_html( $field['title'] ); ?>
                        </label>
                        <label class="switch">
                            <input 
                                type="checkbox" 
                                id="<?php echo esc_attr( $option_name ); ?>" 
                                name="<?php echo esc_attr( $option_name ); ?>" 
                                value="1" 
                                <?php checked( $option_value, 1 ); ?>
                            >
                            <span class="slider"></span>
                        </label>
                        <span class="status-text"><?php echo $option_value ? __( 'Active', 'dokan-kits' ) : __( 'Inactive', 'dokan-kits' ); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}