<?php
namespace DokanKits\Core;

/**
 * Dependencies Class
 *
 * @package DokanKits\Core
 */
class Dependencies {
    /**
     * Check dependencies
     *
     * @return bool True if all dependencies are met, false otherwise
     */
    public function check() {
        // Check if Dokan is active
        if ( ! $this->is_dokan_active() ) {
            add_action( 'admin_notices', [ $this, 'dokan_dependency_notice' ] );
            add_action( 'admin_init', [ $this, 'deactivate_plugin' ] );
            return false;
        }

        /**
         * Filter dependencies check result
         *
         * @param bool $result Check result
         */
        return apply_filters( 'dokan_kits_dependencies_check', true );
    }

    /**
     * Check if Dokan is active using multiple reliable methods
     *
     * @return bool
     */
    public function is_dokan_active() {
        // Method 1: Check for class existence
        $class_exists = class_exists( 'WeDevs\Dokan\Dokan' );
        
        // Method 2: Check for constant definition
        $constant_defined = defined( 'DOKAN_PLUGIN_VERSION' );
        
        // Method 3: Check in active plugins list
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        $plugin_in_list = in_array( 'dokan-lite/dokan.php', $active_plugins );
        
        // Method 4: Check using is_plugin_active function
        $is_active = false;
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        if ( function_exists( 'is_plugin_active' ) ) {
            $is_active = is_plugin_active( 'dokan-lite/dokan.php' );
        }
        
        // Log detection results for debugging
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'Dokan Kits Dependency Check:' );
            error_log( '- Class exists: ' . ( $class_exists ? 'Yes' : 'No' ) );
            error_log( '- Constant defined: ' . ( $constant_defined ? 'Yes' : 'No' ) );
            error_log( '- Plugin in list: ' . ( $plugin_in_list ? 'Yes' : 'No' ) );
            error_log( '- is_plugin_active: ' . ( $is_active ? 'Yes' : 'No' ) );
        }
        
        // Consider Dokan active if ANY of the checks pass
        return $class_exists || $constant_defined || $plugin_in_list || $is_active;
    }

    /**
     * Display admin notice for Dokan dependency
     *
     * @return void
     */
    public function dokan_dependency_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'Dokan Kits requires Dokan plugin to be installed and activated.', 'dokan-kits' ); ?></p>
            <p><a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=dokan&tab=search&type=term' ) ); ?>" class="button-primary"><?php _e( 'Install Dokan', 'dokan-kits' ); ?></a></p>
        </div>
        <?php
    }

    /**
     * Deactivate the plugin
     *
     * @return void
     */
    public function deactivate_plugin() {
        if ( ! function_exists( 'deactivate_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        deactivate_plugins( DOKAN_KITS_BASENAME );
        
        // If URL has plugin activation parameter, remove it
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }

    /**
     * Check if Dokan Pro is active
     *
     * @return bool
     */
    public function is_dokan_pro_active() {
        // Check for class existence
        $class_exists = class_exists( 'DokanPro' );
        
        // Check for constant definition
        $constant_defined = defined( 'DOKAN_PRO_PLUGIN_VERSION' );
        
        // Check in active plugins list
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        $plugin_in_list = in_array( 'dokan-pro/dokan-pro.php', $active_plugins );
        
        // Check using is_plugin_active function
        $is_active = false;
        if ( function_exists( 'is_plugin_active' ) ) {
            $is_active = is_plugin_active( 'dokan-pro/dokan-pro.php' );
        }
        
        return $class_exists || $constant_defined || $plugin_in_list || $is_active;
    }
}