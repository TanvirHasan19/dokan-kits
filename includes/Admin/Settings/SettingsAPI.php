<?php
namespace DokanKits\Admin\Settings;

use DokanKits\Core\Container;

/**
 * Settings API
 *
 * @package DokanKits\Admin\Settings
 */
class SettingsAPI {
    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Settings array
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
        $this->settings = get_option( 'dokan_kits_settings', [] );
    }

    /**
     * Get setting
     *
     * @param string $key     Setting key
     * @param mixed  $default Default value
     *
     * @return mixed
     */
    public function get( $key, $default = null ) {
        // First check individual option (legacy support)
        $value = get_option( $key, null );
        
        if ( null !== $value ) {
            return $value;
        }
        
        // Check in settings array
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
    }

    /**
     * Update setting
     *
     * @param string $key   Setting key
     * @param mixed  $value Setting value
     *
     * @return bool
     */
    public function update( $key, $value ) {
        // First update individual option (legacy support)
        update_option( $key, $value );
        
        // Update in settings array
        $this->settings[ $key ] = $value;
        
        return update_option( 'dokan_kits_settings', $this->settings );
    }

    /**
     * Delete setting
     *
     * @param string $key Setting key
     *
     * @return bool
     */
    public function delete( $key ) {
        // First delete individual option (legacy support)
        delete_option( $key );
        
        // Delete from settings array
        if ( isset( $this->settings[ $key ] ) ) {
            unset( $this->settings[ $key ] );
            return update_option( 'dokan_kits_settings', $this->settings );
        }
        
        return true;
    }

    /**
     * Get all settings
     *
     * @return array
     */
    public function get_all() {
        return $this->settings;
    }

    /**
     * Update multiple settings
     *
     * @param array $settings Settings array
     *
     * @return bool
     */
    public function update_all( $settings ) {
        // Update individual options (legacy support)
        foreach ( $settings as $key => $value ) {
            update_option( $key, $value );
        }
        
        // Update settings array
        $this->settings = array_merge( $this->settings, $settings );
        
        return update_option( 'dokan_kits_settings', $this->settings );
    }

    /**
     * Check if setting exists
     *
     * @param string $key Setting key
     *
     * @return bool
     */
    public function exists( $key ) {
        // Check individual option (legacy support)
        if ( get_option( $key, null ) !== null ) {
            return true;
        }
        
        // Check in settings array
        return isset( $this->settings[ $key ] );
    }

    /**
     * Check if setting is enabled
     *
     * @param string $key Setting key
     *
     * @return bool
     */
    public function is_enabled( $key ) {
        return $this->get( $key, false ) === '1';
    }

    /**
     * Register setting
     *
     * @param string $key         Setting key
     * @param array  $args        Setting arguments
     * @param string $option_group Option group (default: dokan_kits_settings_group)
     *
     * @return void
     */
    public function register( $key, $args = [], $option_group = 'dokan_kits_settings_group' ) {
        register_setting( $option_group, $key, $args );
    }
}