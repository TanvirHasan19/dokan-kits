<?php
namespace DokanKits\Admin;

use DokanKits\Core\Container;

/**
 * Menu Class
 *
 * @package DokanKits\Admin
 */
class Menu {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Container $container Container instance
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Initialize menu
     *
     * @return void
     */
    public function init() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function register_menu() {
        // Get custom icon URL
        $icon_url = $this->get_menu_icon_url();
        
        // Add menu page
        $hook_suffix = add_menu_page(
            __( 'Dokan Kits', 'dokan-kits' ),
            __( 'Dokan Kits', 'dokan-kits' ),
            'manage_options',
            'dokan-kits',
            [ $this, 'settings_page' ],
            $icon_url,
            56
        );

        // Add action for loading assets
        add_action( 'load-' . $hook_suffix, [ $this, 'load_assets' ] );

        /**
         * Action after menu registration
         *
         * @param string $hook_suffix Hook suffix
         * @param Menu   $this        Menu instance
         */
        do_action( 'dokan_kits_admin_menu_registered', $hook_suffix, $this );
    }

    /**
     * Get menu icon URL
     *
     * @return string
     */
    protected function get_menu_icon_url() {
        // Default icon (Dashicon)
        $icon_url = 'dashicons-editor-unlink';

        // Check if custom icon exists
        $icon_path = DOKAN_KITS_PLUGIN_PATH . 'assets/images/icon-20x20.png';
        
        if ( file_exists( $icon_path ) ) {
            $icon_url = DOKAN_KITS_ASSETS_URL . '/images/icon-20x20.png';
        }

        /**
         * Filter menu icon URL
         *
         * @param string $icon_url Menu icon URL
         * @param Menu   $this     Menu instance
         */
        return apply_filters( 'dokan_kits_admin_menu_icon_url', $icon_url, $this );
    }

    /**
     * Settings page callback
     *
     * @return void
     */
    public function settings_page() {
        try {
            $settings_page = $this->container->get( 'admin.settings.page' );
            $settings_page->render();
        } catch (\Exception $e) {
            // Fallback if settings page class is not found
            ?>
            <div class="wrap">
                <h1><?php _e( 'Dokan Kits Settings', 'dokan-kits' ); ?></h1>
                <p><?php _e( 'This plugin provides you with tools to enhance your Dokan experience.', 'dokan-kits' ); ?></p>
                
                <form method="post" action="options.php">
                    <?php 
                    settings_fields( 'dokan_kits_settings_group' );
                    do_settings_sections( 'dokan_kits_settings_group' );
                    
                    // Simple settings form
                    ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'Remove Vendor Registration', 'dokan-kits' ); ?></th>
                            <td>
                                <input type="checkbox" name="remove_vendor_checkbox" value="1" <?php checked( get_option( 'remove_vendor_checkbox' ), 1 ); ?>>
                                <?php _e( 'Remove "I am a vendor" option from the WooCommerce my account page.', 'dokan-kits' ); ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e( 'Enable "I am a Vendor" by default', 'dokan-kits' ); ?></th>
                            <td>
                                <input type="checkbox" name="set_default_seller_role_checkbox" value="1" <?php checked( get_option( 'set_default_seller_role_checkbox' ), 1 ); ?>>
                                <?php _e( 'To enable the "I am a Vendor" option by default on the My Account page.', 'dokan-kits' ); ?>
                            </td>
                        </tr>
                        
                        <!-- Add more settings as needed -->
                    </table>
                    
                    <?php submit_button( __( 'Save Changes', 'dokan-kits' ) ); ?>
                </form>
            </div>
            <?php
        }
    }

    /**
     * Load assets for settings page
     *
     * @return void
     */
    public function load_assets() {
        try {
            $assets = $this->container->get( 'admin.assets' );
            $assets->enqueue_settings_assets();
        } catch (\Exception $e) {
            // Fallback if assets class is not found
            // Do nothing as basic admin.css and admin.js will be loaded by Admin::init_assets
        }
    }
}