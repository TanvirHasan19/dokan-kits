<?php
namespace DokanKits\Admin\Settings;

use DokanKits\Core\Container;

/**
 * Settings Page
 *
 * @package DokanKits\Admin\Settings
 */
class Page {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Settings instance
     *
     * @var Settings
     */
    protected $settings;

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
        
        try {
            $this->settings = $container->get( 'admin.settings' );
        } catch (\Exception $e) {
            // Create a settings instance if not available
            $this->settings = new Settings( $container );
        }
    }

    /**
     * Render settings page
     *
     * @return void
     */
    public function render() {
        // Get active tab
        $active_tab = $this->get_active_tab();
        
        // Get tabs
        $tabs = $this->settings->get_tabs();
        
        // Start output
        ?>
        <div class="dokan-kits-wrap">
            <?php $this->render_header(); ?>

            <div class="dokan-kits-tabs-wrapper">
                <?php 
                if (!empty($tabs)) {
                    $this->render_tabs_navigation($tabs, $active_tab);
                    $this->render_tab_content($tabs, $active_tab);
                } else {
                    // Fallback to simple form if no tabs are available
                    $this->render_simple_form();
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render header
     *
     * @return void
     */
    protected function render_header() {
        ?>
        <div class="dokan_kits_description-box">           
            <div class="dokan-kits-head-logo">
                <img src="<?php echo DOKAN_KITS_ASSETS_URL . '/images/dokan-kits-logo.png'; ?>" alt="Dokan Kits Logo" class="dokan-kits-logo">
            </div>
            <div class="description-box">
                <h1><?php _e( 'Dokan Kits Settings', 'dokan-kits' ); ?></h1>
                <h3 class="additional-text"><?php _e( 'This plugin provides you with tools to enhance your Dokan experience. Use this plugin to remove or modify various elements and more.', 'dokan-kits' ); ?></h3>
            </div>
        </div>
        <?php
    }

    /**
     * Render tabs navigation
     *
     * @param array  $tabs       Tabs
     * @param string $active_tab Active tab
     *
     * @return void
     */
    protected function render_tabs_navigation( $tabs, $active_tab ) {
        ?>
        <div class="dokan-kits-tabs-navigation">
            <ul class="dokan-kits-tabs">
                <?php foreach ( $tabs as $tab_id => $tab ) : ?>
                    <li class="dokan-kits-tab <?php echo $active_tab === $tab_id ? 'active' : ''; ?>">
                        <a href="<?php echo admin_url( 'admin.php?page=dokan-kits&tab=' . $tab_id ); ?>">
                            <i class="fa <?php echo esc_attr( $tab->get_icon() ); ?>"></i>
                            <?php echo esc_html( $tab->get_title() ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Render tab content
     *
     * @param array  $tabs       Tabs
     * @param string $active_tab Active tab
     *
     * @return void
     */
    protected function render_tab_content( $tabs, $active_tab ) {
        ?>
        <div class="dokan-kits-tab-content">
            <form method="post" action="options.php">
                <?php 
                settings_fields( 'dokan_kits_settings_group' );

                error_log( '$tabs[ $active_tab ]' . print_r( $tabs[ $active_tab ], true));
                
                // Render the active tab content
                if ( isset( $tabs[ $active_tab ] ) ) {
                    $tabs[ $active_tab ]->render();
                }
                ?>
                
                <div id="dokan_kits_save_ch">
                    <?php submit_button( __( 'Save Changes', 'dokan-kits' ), 'primary', 'save_changes_button' ); ?>
                    <div class="save-changes-message" style="display: none;"><?php _e( 'Changes saved successfully!', 'dokan-kits' ); ?></div>
                </div>
            </form>
        </div>
        <?php
    }

    /**
     * Render simple form
     * 
     * This is a fallback if no tabs are available
     *
     * @return void
     */
    protected function render_simple_form() {
        ?>
        <div class="dokan-kits-tab-content">
            <form method="post" action="options.php">
                <?php 
                settings_fields( 'dokan_kits_settings_group' );
                ?>
                
                <div id="dokan-kits-body-content">
                    <!-- Vendor options -->
                    <div class="dokan_kits_style_box">
                        <i class="fa fa-users fa-3x"></i>
                        <div class="toggle-label">
                            <label for="remove_vendor_checkbox" class="for_title_label">
                                <?php _e( 'Remove Vendor Registration', 'dokan-kits' ); ?>
                            </label>
                            <label class="switch">
                                <input 
                                    type="checkbox" 
                                    id="remove_vendor_checkbox" 
                                    name="remove_vendor_checkbox" 
                                    value="1" 
                                    <?php checked( get_option( 'remove_vendor_checkbox' ), 1 ); ?>
                                >
                                <span class="slider"></span>
                            </label>
                            <span class="status-text">
                                <?php echo get_option( 'remove_vendor_checkbox' ) ? __( 'Active', 'dokan-kits' ) : __( 'Inactive', 'dokan-kits' ); ?>
                            </span>
                        </div>
                        <p class="additional-text">
                            <?php _e( 'Remove "I am a vendor" option from the WooCommerce my account page.', 'dokan-kits' ); ?>
                        </p>
                    </div>
                    
                    <div class="dokan_kits_style_box">
                        <i class="fa fa-user-check fa-3x"></i>
                        <div class="toggle-label">
                            <label for="set_default_seller_role_checkbox" class="for_title_label">
                                <?php _e( 'Enable "I am a Vendor" by default', 'dokan-kits' ); ?>
                            </label>
                            <label class="switch">
                                <input 
                                    type="checkbox" 
                                    id="set_default_seller_role_checkbox" 
                                    name="set_default_seller_role_checkbox" 
                                    value="1" 
                                    <?php checked( get_option( 'set_default_seller_role_checkbox' ), 1 ); ?>
                                >
                                <span class="slider"></span>
                            </label>
                            <span class="status-text">
                                <?php echo get_option( 'set_default_seller_role_checkbox' ) ? __( 'Active', 'dokan-kits' ) : __( 'Inactive', 'dokan-kits' ); ?>
                            </span>
                        </div>
                        <p class="additional-text">
                            <?php _e( 'To enable the "I am a Vendor" option by default on the My Account page.', 'dokan-kits' ); ?>
                        </p>
                    </div>
                    
                    <!-- More settings boxes go here -->
                </div>
                
                <div id="dokan_kits_save_ch">
                    <?php submit_button( __( 'Save Changes', 'dokan-kits' ), 'primary', 'save_changes_button' ); ?>
                    <div class="save-changes-message" style="display: none;"><?php _e( 'Changes saved successfully!', 'dokan-kits' ); ?></div>
                </div>
            </form>
        </div>
        <?php
    }

    /**
     * Get active tab
     *
     * @return string
     */
    protected function get_active_tab() {
        $default_tab = 'vendor';
        $tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : $default_tab;
        
        // Validate tab
        $tabs = $this->settings->get_tabs();
        
        if ( ! isset( $tabs[ $tab ] ) ) {
            $tab = $default_tab;
        }
        
        /**
         * Filter active tab
         *
         * @param string $tab  Active tab
         * @param Page   $this Page instance
         */
        return apply_filters( 'dokan_kits_settings_active_tab', $tab, $this );
    }
}