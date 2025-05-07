<?php
/**
 * Dokan Kits Helper Functions
 *
 * @package DokanKits
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get plugin instance - wrapper for the global function for IDE autocompletion
 *
 * @return Dokan_Kits
 */
function dokan_kits_get_instance() {
    return dokan_kits();
}

/**
 * Get container instance
 *
 * @return DokanKits\Core\Container
 */
function dokan_kits_container() {
    return dokan_kits()->container();
}

/**
 * Get plugin setting
 *
 * @param string $key     Setting key
 * @param mixed  $default Default value
 *
 * @return mixed
 */
function dokan_kits_get_option( $key, $default = null ) {
    // First check individual option (legacy support)
    $value = get_option( $key, null );
    
    if ( null !== $value ) {
        return $value;
    }
    
    // Check in settings array
    $settings = get_option( 'dokan_kits_settings', [] );
    
    return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}

/**
 * Update plugin setting
 *
 * @param string $key   Setting key
 * @param mixed  $value Setting value
 *
 * @return bool
 */
function dokan_kits_update_option( $key, $value ) {
    // First update individual option (legacy support)
    update_option( $key, $value );
    
    // Update in settings array
    $settings = get_option( 'dokan_kits_settings', [] );
    $settings[ $key ] = $value;
    
    return update_option( 'dokan_kits_settings', $settings );
}

/**
 * Check if a feature is enabled
 *
 * @param string $feature_id Feature ID
 *
 * @return bool
 */
function dokan_kits_is_feature_enabled( $feature_id ) {
    return dokan_kits_get_option( $feature_id, false ) === '1';
}

/**
 * Get template part
 *
 * @param string $slug Template slug
 * @param string $name Template name (optional)
 * @param array  $args Template arguments (optional)
 *
 * @return void
 */
function dokan_kits_get_template_part( $slug, $name = '', $args = [] ) {
    // Extract args
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }
    
    // Look in theme/child theme
    $template = '';
    
    // Get template path from theme
    if ( $name ) {
        $template = locate_template( [ "dokan-kits/{$slug}-{$name}.php" ] );
    }
    
    // Get default slug template
    if ( ! $template && $name && file_exists( DOKAN_KITS_TEMPLATE_PATH . "/{$slug}-{$name}.php" ) ) {
        $template = DOKAN_KITS_TEMPLATE_PATH . "/{$slug}-{$name}.php";
    }
    
    // If template not found, get default template
    if ( ! $template ) {
        $template = locate_template( [ "dokan-kits/{$slug}.php" ] );
    }
    
    // Get default template
    if ( ! $template && file_exists( DOKAN_KITS_TEMPLATE_PATH . "/{$slug}.php" ) ) {
        $template = DOKAN_KITS_TEMPLATE_PATH . "/{$slug}.php";
    }
    
    // Allow 3rd party plugins to filter template file
    $template = apply_filters( 'dokan_kits_get_template_part', $template, $slug, $name, $args );
    
    if ( $template ) {
        include $template;
    }
}

/**
 * Get template
 *
 * @param string $template_name Template name
 * @param array  $args          Template arguments (optional)
 * @param string $template_path Template path (optional)
 * @param string $default_path  Default path (optional)
 *
 * @return void
 */
function dokan_kits_get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
    // Extract args
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }
    
    // Set default path
    if ( ! $default_path ) {
        $default_path = DOKAN_KITS_TEMPLATE_PATH;
    }
    
    // Look in theme/child theme
    $template = locate_template( [
        trailingslashit( $template_path ) . $template_name,
        $template_name,
    ] );
    
    // Get default template
    if ( ! $template && file_exists( trailingslashit( $default_path ) . $template_name ) ) {
        $template = trailingslashit( $default_path ) . $template_name;
    }
    
    // Allow 3rd party plugins to filter template file
    $template = apply_filters( 'dokan_kits_get_template', $template, $template_name, $args, $template_path, $default_path );
    
    if ( $template ) {
        include $template;
    }
}

/**
 * Check if user is a vendor
 *
 * @param int|null $user_id User ID, defaults to current user
 *
 * @return bool
 */
function dokan_kits_is_vendor( $user_id = null ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }
    
    if ( ! $user_id ) {
        return false;
    }
    
    $user = get_userdata( $user_id );
    
    return $user && in_array( 'seller', (array) $user->roles );
}

/**
 * Check if Dokan is active
 *
 * @return bool
 */
function dokan_kits_is_dokan_active() {
    // Get from container if available
    if ( dokan_kits_container() && dokan_kits_container()->has( 'dependencies' ) ) {
        $dependencies = dokan_kits_container()->get( 'dependencies' );
        return $dependencies->is_dokan_active();
    }
    
    // Fallback method
    return class_exists( 'WeDevs\Dokan\Dokan' );
}

/**
 * Check if Dokan Pro is active
 *
 * @return bool
 */
function dokan_kits_is_dokan_pro_active() {
    // Get from container if available
    if ( dokan_kits_container() && dokan_kits_container()->has( 'dependencies' ) ) {
        $dependencies = dokan_kits_container()->get( 'dependencies' );
        return $dependencies->is_dokan_pro_active();
    }
    
    // Fallback method
    return class_exists( 'DokanPro' );
}

/**
 * Format file size to human readable format
 * 
 * @param int $bytes     Size in bytes
 * @param int $precision Precision (default: 2)
 * 
 * @return string Formatted size
 */
function dokan_kits_format_file_size( $bytes, $precision = 2 ) {
    if ( function_exists( 'size_format' ) ) {
        return size_format( $bytes, $precision );
    }
    
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max( $bytes, 0 );
    $pow = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
    $pow = min( $pow, count( $units ) - 1 );
    
    $bytes /= pow( 1024, $pow );
    
    return round( $bytes, $precision ) . ' ' . $units[ $pow ];
}

/**
 * Get available product types
 *
 * @return array
 */
function dokan_kits_get_product_types() {
    $product_types = [];
    
    if ( function_exists( 'dokan_get_product_types' ) ) {
        $product_types = dokan_get_product_types();
    } else {
        // Default WooCommerce product types
        $product_types = [
            'simple'   => __( 'Simple', 'dokan-kits' ),
            'variable' => __( 'Variable', 'dokan-kits' ),
            'external' => __( 'External/Affiliate', 'dokan-kits' ),
            'grouped'  => __( 'Grouped', 'dokan-kits' ),
        ];
    }
    
    return apply_filters( 'dokan_kits_product_types', $product_types );
}

/**
 * Get premium features status
 *
 * @return bool
 */
function dokan_kits_has_premium() {
    return apply_filters( 'dokan_kits_has_premium', false );
}

/**
 * Debug function to log messages
 *
 * @param mixed $message Message to log
 */
function dokan_kits_debug_log( $message ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
        if ( is_array( $message ) || is_object( $message ) ) {
            error_log( print_r( $message, true ) );
        } else {
            error_log( $message );
        }
    }
}

/**
 * Sanitize array recursively
 *
 * @param array $array Array to sanitize
 *
 * @return array
 */
function dokan_kits_sanitize_array( $array ) {
    foreach ( $array as $key => $value ) {
        if ( is_array( $value ) ) {
            $array[ $key ] = dokan_kits_sanitize_array( $value );
        } else {
            $array[ $key ] = sanitize_text_field( $value );
        }
    }
    
    return $array;
}

/**
 * Register hook handler based on hook type (action or filter)
 *
 * @param string   $hook_type  Hook type (action or filter)
 * @param string   $hook_name  Hook name
 * @param callable $callback   Callback function
 * @param int      $priority   Priority (default: 10)
 * @param int      $accepted_args Accepted args (default: 1)
 */
function dokan_kits_register_hook( $hook_type, $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
    if ( $hook_type === 'action' ) {
        add_action( $hook_name, $callback, $priority, $accepted_args );
    } elseif ( $hook_type === 'filter' ) {
        add_filter( $hook_name, $callback, $priority, $accepted_args );
    }
}

/**
 * Register action
 *
 * @param string   $hook_name  Hook name
 * @param callable $callback   Callback function
 * @param int      $priority   Priority (default: 10)
 * @param int      $accepted_args Accepted args (default: 1)
 */
function dokan_kits_register_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
    dokan_kits_register_hook( 'action', $hook_name, $callback, $priority, $accepted_args );
}

/**
 * Register filter
 *
 * @param string   $hook_name  Hook name
 * @param callable $callback   Callback function
 * @param int      $priority   Priority (default: 10)
 * @param int      $accepted_args Accepted args (default: 1)
 */
function dokan_kits_register_filter( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
    dokan_kits_register_hook( 'filter', $hook_name, $callback, $priority, $accepted_args );
}